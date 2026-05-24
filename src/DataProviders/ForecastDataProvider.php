<?php

namespace StockForecastForWooCommerce\DataProviders;

use StockForecastForWooCommerce\Abstracts\AbstractDataProvider;
use StockForecastForWooCommerce\Models\Forecast;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ForecastDataProvider
 *
 * @package StockForecastForWooCommerce\DataProviders
 * @version 1.0.0
 */
class ForecastDataProvider extends AbstractDataProvider
{
    /**
     * Return aggregated counts from the forecasts table.
     *
     * @return array
     */
    public function getForecastStats(): array
    {
        global $wpdb;

        $table = Forecast::getTableName();

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $sql = "
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN risk_level = 'safe' THEN 1 ELSE 0 END) as safe,
                SUM(CASE WHEN risk_level = 'warning' THEN 1 ELSE 0 END) as warning,
                SUM(CASE WHEN risk_level = 'critical' THEN 1 ELSE 0 END) as critical,
                SUM(CASE WHEN risk_level = 'out_of_stock' THEN 1 ELSE 0 END) as out_of_stock,
                SUM(CASE WHEN risk_level = 'backordering' THEN 1 ELSE 0 END) as backordering
            FROM {$table}
        ";

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter
        $row = $wpdb->get_row($sql, ARRAY_A);

        return [
            'total'        => $row['total'],
            'safe'         => $row['safe'],
            'warning'      => $row['warning'],
            'critical'     => $row['critical'],
            'out_of_stock' => $row['out_of_stock'],
            'backordering' => $row['backordering'],
        ];
    }

    /**
     * Get product IDs with stale or missing forecasts.
     *
     * @param int $limit
     * @param int $afterId
     * @return int[]
     */
    public function getStaleForecastProductIds(int $limit, int $afterId = 0): array
    {
        global $wpdb;

        $table = Forecast::getTableName();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
        $ids = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT product_id FROM {$table}
        WHERE (last_calculated IS NULL OR last_calculated < NOW() - INTERVAL 7 DAY)
        AND product_id > %d
        ORDER BY product_id ASC
        LIMIT %d
        ",
            $afterId,
            $limit
        ));

        return array_map('intval', $ids);
    }

    /**
     * Get critical products ordered by nearest stockout.
     *
     * @param int $limit
     * @return array
     */
    public function getCriticalProducts(int $limit = 10): array
    {
        global $wpdb;

        $table = Forecast::getTableName();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
        $rows = $wpdb->get_results($wpdb->prepare("SELECT product_id, variation_id, current_stock, daily_sales, days_until_stockout, risk_level FROM {$table}
            WHERE risk_level = 'critical'
            ORDER BY days_until_stockout ASC
            LIMIT %d
        ",
            $limit
        ));

        return array_map(static fn($row) => Forecast::make((array)$row), $rows);
    }

    /**
     * Get paginated forecasts with filters.
     *
     * @param int $perPage
     * @param int $page
     * @param string $orderBy
     * @param string $order
     * @param array $filters
     * @return array
     */
    public function getForecasts(
        int    $perPage = 20,
        int    $page = 1,
        string $orderBy = 'id',
        string $order = 'DESC',
        array  $filters = []
    ): array
    {
        global $wpdb;

        $table  = Forecast::getTableName();
        $offset = ($page - 1) * $perPage;

        [$join, $whereSql, $params] = $this->buildForecastFilters($filters, $table);
        [$orderBy, $order] = $this->sanitizeSorting($orderBy, $order, $table, [
            'id', 'days_until_stockout', 'current_stock', 'daily_sales', 'risk_level', 'last_calculated'
        ]);

        $countSql = "SELECT COUNT(*) FROM {$table} {$join} {$whereSql}";

        if (!empty($params)) {
            // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
            $total = (int)$wpdb->get_var($wpdb->prepare($countSql, ...$params));
        } else {
            // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
            $total = (int)$wpdb->get_var($countSql);
        }

        $paramsWithLimit = [...$params, $perPage, $offset];
        $sql             = "
            SELECT {$table}.*
            FROM {$table}
            {$join}
            {$whereSql}
            ORDER BY {$orderBy} {$order}
            LIMIT %d OFFSET %d
        ";

        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
        $rows = $wpdb->get_results($wpdb->prepare($sql, ...$paramsWithLimit), ARRAY_A);

        return $this->formatPaginatedResult($rows, $total, $perPage, Forecast::class);
    }

    /**
     * Unique forecast-specific filters
     *
     * @param array $filters
     * @param string $table
     * @return array
     */
    private function buildForecastFilters(array $filters, string $table): array
    {
        global $wpdb;

        $join   = '';
        $where  = [];
        $params = [];

        if (!empty($filters['risk_level'])) {
            $where[]  = "{$table}.risk_level = %s";
            $params[] = $filters['risk_level'];
        }

        if (!empty($filters['product_type'])) {
            $where[]  = "{$table}.product_type = %s";
            $params[] = $filters['product_type'];
        }

        if (!empty($filters['search'])) {
            $search  = trim($filters['search']);
            $join    = "
                LEFT JOIN {$wpdb->posts} p 
                ON p.ID = {$table}.product_id
                AND p.post_type IN ('product','product_variation')
                AND p.post_status = 'publish'
            ";
            $where[] = "({$table}.sku LIKE %s OR p.post_title LIKE %s)";
            $like    = '%' . $wpdb->esc_like($search) . '%';
            array_push($params, $like, $like);
        }

        $this->applyMaxFilter($filters['days_until_stockout'] ?? null, $where, $params, 'days_until_stockout', $table);
        $this->applyNumericFilter($filters['current_stock'] ?? null, $where, $params, 'current_stock', $table);
        $this->applyNumericFilter($filters['daily_sales'] ?? null, $where, $params, 'daily_sales', $table);

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        return [$join, $whereSql, $params];
    }
}
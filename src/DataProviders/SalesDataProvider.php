<?php

namespace StockForecastForWooCommerce\DataProviders;

use StockForecastForWooCommerce\Abstracts\AbstractDataProvider;
use StockForecastForWooCommerce\Utils\DateTimeUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SalesDataProvider
 *
 * Handles Aggregated Sales Retrieval.
 *
 * @package StockForecastForWooCommerce\DataProviders
 * @version 1.0.0
 */
class SalesDataProvider extends AbstractDataProvider
{
    /**
     * Aggregates sales from WC lookup tables.
     *
     * @param array $entities
     * @param int $days
     * @return array
     */
    public function getAggregatedSales(array $entities, int $days): array
    {
        global $wpdb;

        $productIds = array_unique(array_map('intval', array_column($entities, 'product_id')));

        if (empty($productIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '%d'));
        $dateLimit    = DateTimeUtils::current()
            ->modify("-{$days} days")
            ->format(DateTimeUtils::FORMAT_DATETIME);

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "
                    SELECT opl.product_id, opl.variation_id, SUM(opl.product_qty) as total_sold
                    FROM {$wpdb->prefix}wc_order_product_lookup opl
                    INNER JOIN {$wpdb->prefix}wc_order_stats os 
                        ON os.order_id = opl.order_id WHERE opl.product_id IN ($placeholders) AND opl.date_created >= %s AND os.status IN ('wc-processing','wc-completed') GROUP BY opl.product_id, opl.variation_id", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
                ...array_merge($productIds, [$dateLimit])
            ),
            ARRAY_A
        );

        $salesMap = [];

        foreach ($results as $row) {

            $key = $row['product_id'] . '_' . ($row['variation_id'] ?: 0);

            $salesMap[$key] = (float)$row['total_sold'];
        }

        return $salesMap;
    }
}
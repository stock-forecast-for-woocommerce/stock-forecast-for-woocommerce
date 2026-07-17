<?php

namespace StockForecastForWooCommerce\Models;

use StockForecastForWooCommerce\Abstracts\AbstractModel;
use StockForecastForWooCommerce\Database\QueryRunner;
use StockForecastForWooCommerce\Utils\DateTimeUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Represents a forecast record.
 *
 * @package StockForecastForWooCommerce\Models
 * @since   1.0.0
 */
class Forecast extends AbstractModel
{
    /** Table name (without prefix). */
    protected static string $table = 'forecasts';

    /** Fillable fields. */
    protected static array $fillable = [
        'product_id',
        'variation_id',
        'sku',
        'product_type',
        'current_stock',
        'daily_sales',
        'days_until_stockout',
        'risk_level',
        'last_calculated',
    ];

    /** Insert or update multiple forecast records. */
    public static function upsertMany(array $data)
    {
        if (empty($data)) {
            return 0;
        }

        global $wpdb;

        $table = self::getTableName();

        if (empty($table)) {
            return false;
        }

        $nowRaw = DateTimeUtils::now();

        $values = [];

        $prepareNullableString = static function ($value) use ($wpdb) {
            if (is_null($value)) {
                return 'NULL';
            }
            return $wpdb->prepare('%s', $value);
        };

        $prepareNullableNumber = static function ($value) {
            if (is_null($value)) {
                return 'NULL';
            }
            return (float)$value;
        };

        $prepareNullableDateTime = static function ($value) use ($wpdb) {
            if (is_null($value)) {
                return 'NULL';
            }
            return $wpdb->prepare('%s', $value);
        };

        foreach ($data as $row) {
            $sku         = $prepareNullableString($row['sku'] ?? null);
            $productType = $prepareNullableString($row['product_type'] ?? null);

            if (!isset($row['current_stock'])) {
                $currentStock = 'NULL';
            } else {
                $currentStock = (int)$row['current_stock'];
            }

            $dailySales        = $prepareNullableNumber($row['daily_sales'] ?? null);
            $daysUntilStockout = $prepareNullableNumber($row['days_until_stockout'] ?? null);

            $riskLevel = $prepareNullableString($row['risk_level'] ?? null);

            $lastCalculated = $prepareNullableDateTime($row['last_calculated'] ?? $nowRaw);
            $createdAt      = $wpdb->prepare('%s', $nowRaw);
            $updatedAt      = $prepareNullableDateTime($row['updated_at'] ?? $nowRaw);

            $productId   = (int)$row['product_id'];
            $variationId = (int)$row['variation_id'];

            $values[] = "($productId, $variationId, $sku, $productType, $currentStock, $dailySales, $daysUntilStockout, $riskLevel, $lastCalculated, $createdAt, $updatedAt)";
        }

        $valuesSql = implode(',', $values);

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
        $sql = "
        INSERT INTO $table 
        (product_id, variation_id, sku, product_type, current_stock, daily_sales, days_until_stockout, risk_level, last_calculated, created_at, updated_at)
        VALUES $valuesSql
        ON DUPLICATE KEY UPDATE 
            sku = VALUES(sku),
            product_type = VALUES(product_type),
            current_stock = VALUES(current_stock),
            daily_sales = VALUES(daily_sales),
            days_until_stockout = VALUES(days_until_stockout),
            risk_level = VALUES(risk_level),
            last_calculated = VALUES(last_calculated),
            updated_at = VALUES(updated_at)
    ";

        return QueryRunner::query($sql);
    }

    /** Delete multiple forecast records. */
    public static function deleteMany(array $entities)
    {
        if (empty($entities)) {
            return 0;
        }

        global $wpdb;
        $table = self::getTableName();

        if (empty($table)) {
            return false;
        }

        $whereClauses = [];
        $params       = [];

        foreach ($entities as $entity) {
            $productId   = (int)($entity['product_id'] ?? 0);
            $variationId = (int)($entity['variation_id'] ?? 0);

            if ($productId > 0) {
                $whereClauses[] = '(product_id = %d AND variation_id = %d)';
                $params[]       = $productId;
                $params[]       = $variationId;
            }
        }

        if (empty($whereClauses)) {
            return 0;
        }

        $whereSql = implode(' OR ', $whereClauses);

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        $sql = $wpdb->prepare("DELETE FROM $table WHERE $whereSql", ...$params);

        return QueryRunner::query($sql);
    }

    /** Delete all forecast records for a product. */
    public static function deleteByProductId(int $productId)
    {
        if ($productId <= 0) {
            return 0;
        }

        global $wpdb;
        $table = self::getTableName();

        if (empty($table)) {
            return false;
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        return QueryRunner::query($wpdb->prepare("DELETE FROM $table WHERE product_id = %d", $productId));
    }

    /** Delete a forecast record for a variation. */
    public static function deleteByVariationId(int $variationId)
    {
        if ($variationId <= 0) {
            return 0;
        }

        global $wpdb;
        $table = self::getTableName();

        if (empty($table)) {
            return false;
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
        return QueryRunner::query($wpdb->prepare("DELETE FROM $table WHERE variation_id = %d", $variationId));
    }
}
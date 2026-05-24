<?php

namespace StockForecastForWooCommerce\Models;

use StockForecastForWooCommerce\Abstracts\AbstractModel;
use StockForecastForWooCommerce\Database\DatabaseManager;
use StockForecastForWooCommerce\Utils\DateTimeUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @package StockForecastForWooCommerce\Models
 * @version 1.0.0
 */
class Forecast extends AbstractModel
{
    /**
     * Table name (without prefix)
     *
     * @var string
     */
    protected static string $table = 'forecasts';

    /**
     * Fillable fields (allowed for mass assignment)
     *
     * @var array
     */
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

    /**
     * Bulk Upsert (Insert or Update on Duplicate Key)
     *
     * @param array $data Array of forecast data arrays.
     * @return int|bool Number of affected rows or false.
     */
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

            if (!isset($row['current_stock']) || is_null($row['current_stock'])) {
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

        return DatabaseManager::query($sql);
    }

    /**
     * Bulk Delete (Delete multiple records based on product_id and variation_id pairs).
     *
     * @param array $entities An array of associative arrays, each containing 'product_id' and 'variation_id'.
     * @return int|bool Number of affected rows or false on failure.
     */
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

        return DatabaseManager::query($sql);
    }

    /**
     * Delete all forecast records related to a product.
     *
     * This method removes forecasts for:
     * - the main product (variation_id = 0)
     * - all its variations
     *
     * Intended for use in product deletion hooks.
     *
     * @param int $productId WooCommerce product ID.
     * @return int|false Number of affected rows or false on failure.
     */
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
        return DatabaseManager::query($wpdb->prepare("DELETE FROM $table WHERE product_id = %d", $productId));
    }

    /**
     * Delete a forecast record for a specific product variation.
     *
     * Intended for use in variation deletion hooks.
     *
     * @param int $variationId WooCommerce variation ID.
     * @return int|false Number of affected rows or false on failure.
     */
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
        return DatabaseManager::query($wpdb->prepare("DELETE FROM $table WHERE variation_id = %d", $variationId));
    }
}
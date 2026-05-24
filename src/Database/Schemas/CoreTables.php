<?php

namespace StockForecastForWooCommerce\Database\Schemas;

use StockForecastForWooCommerce\Database\DatabaseManager;
use StockForecastForWooCommerce\Database\TableSchema;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class CoreTables
 *
 * Defines core database tables for the plugin.
 *
 * @package StockForecastForWooCommerce\Database\Schemas
 * @version 1.0.0
 */
class CoreTables
{
    /**
     * Register table creation hooks.
     *
     * @return void
     */
    public static function register(): void
    {
        add_action('stock_forecast_for_woocommerce_create_tables', [self::class, 'createTables']);
    }

    /**
     * Get all table schemas for this provider.
     *
     * @return TableSchema[]
     */
    public static function getSchemas(): array
    {
        return [
            self::getForecastsSchema(),
        ];
    }

    /**
     * Register and create all core tables.
     *
     * @return void
     */
    public static function createTables(): void
    {
        DatabaseManager::registerTable(self::getForecastsSchema());
        DatabaseManager::createTables();
    }

    /**
     * Forecasts table schema.
     *
     * @return TableSchema
     */
    private static function getForecastsSchema(): TableSchema
    {
        $schema = new TableSchema('forecasts');

        $schema
            ->id()
            ->bigInt('product_id')
            ->bigInt('variation_id')->default(0)
            ->varchar('sku', 191)->nullable()
            ->varchar('product_type', 20)->nullable()
            ->int('current_stock', false)->nullable()
            ->decimal('daily_sales', 10, 4)->nullable()
            ->decimal('days_until_stockout')->nullable()
            ->varchar('risk_level', 20)->nullable()
            ->datetime('last_calculated')->nullable()
            ->timestamps()

            // Unique Constraint
            ->unique('forecast_product_variation_unique', ['product_id', 'variation_id'])

            // Search and Filter Indexes
            ->index('forecast_sku_index', ['sku'])
            ->index('forecast_type_index', ['product_type'])
            ->index('forecast_risk_index', ['risk_level'])

            // Sorting and Performance Indexes
            ->index('forecast_stockout_sort_index', ['days_until_stockout'])
            ->index('forecast_stock_sort_index', ['current_stock'])
            ->index('forecast_sales_sort_index', ['daily_sales'])
            ->index('forecast_last_calc_index', ['last_calculated']);

        return $schema;
    }
}
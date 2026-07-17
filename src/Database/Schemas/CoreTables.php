<?php

namespace StockForecastForWooCommerce\Database\Schemas;

use StockForecastForWooCommerce\Database\SchemaBuilder;
use StockForecastForWooCommerce\Database\SchemaRegistry;
use StockForecastForWooCommerce\Database\TableSchema;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Defines core database tables.
 *
 * @package StockForecastForWooCommerce\Database\Schemas
 * @since   1.0.0
 */
class CoreTables
{
    /** Get all table schemas for this provider. */
    public static function getSchemas(): array
    {
        return [
            self::getForecastsSchema(),
        ];
    }

    /** Register and create all core tables. */
    public static function createTables(): void
    {
        SchemaRegistry::registerTable(self::getForecastsSchema());

        SchemaBuilder::createTables();
    }

    /** Forecasts table schema. */
    public static function getForecastsSchema(): TableSchema
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
            ->unique('forecast_product_variation_unique', ['product_id', 'variation_id'])
            ->index('forecast_sku_index', ['sku'])
            ->index('forecast_type_index', ['product_type'])
            ->index('forecast_risk_index', ['risk_level'])
            ->index('forecast_stockout_sort_index', ['days_until_stockout'])
            ->index('forecast_stock_sort_index', ['current_stock'])
            ->index('forecast_sales_sort_index', ['daily_sales'])
            ->index('forecast_last_calc_index', ['last_calculated']);

        return $schema;
    }

}
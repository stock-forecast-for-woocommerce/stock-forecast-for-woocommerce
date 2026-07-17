<?php

namespace StockForecastForWooCommerce\Database;

use StockForecastForWooCommerce\Abstracts\AbstractSingleton;
use StockForecastForWooCommerce\Database\Schemas\CoreTables;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Manages database schema registration at boot time.
 *
 * @package StockForecastForWooCommerce\Database
 * @since   1.0.0
 */
class SchemaManager extends AbstractSingleton
{
    /** Register all table schemas. */
    public function register(): void
    {
        $this->registerCoreTables();
    }

    /** Register core tables schemas. */
    private function registerCoreTables(): void
    {
        if (!class_exists(CoreTables::class) || !method_exists(CoreTables::class, 'getSchemas')) {
            return;
        }

        $schemas = CoreTables::getSchemas();

        foreach ($schemas as $schema) {
            SchemaRegistry::registerTable($schema);
        }
    }
}
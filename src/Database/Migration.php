<?php

namespace StockForecastForWooCommerce\Database;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles database schema migrations.
 *
 * @package StockForecastForWooCommerce\Database
 * @since   1.0.0
 */
class Migration
{
    /** Run all pending migrations. */
    public static function up(): void
    {
        $migrations = [];

        foreach ($migrations as $version => $callback) {
            $result = VersionManager::migrate($version, $callback);

            if (!$result) {
                return;
            }
        }
    }
}
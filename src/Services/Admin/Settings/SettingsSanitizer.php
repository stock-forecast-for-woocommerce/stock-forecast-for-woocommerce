<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\Utils\Sanitize;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SettingsSanitizer
 *
 * Handles sanitization of plugin settings sections.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @version 1.0.0
 */
class SettingsSanitizer
{
    /**
     * Sanitize forecast settings section.
     *
     * @param array $data
     * @return array
     */
    public static function forecast(array $data): array
    {
        return Sanitize::map($data, [
            PluginSettings::SALES_WINDOW_DAYS => 'absint',
            PluginSettings::BATCH_SIZE        => 'absint',
            PluginSettings::WARNING_DAYS      => 'absint',
            PluginSettings::CRITICAL_DAYS     => 'absint',
        ]);
    }
}
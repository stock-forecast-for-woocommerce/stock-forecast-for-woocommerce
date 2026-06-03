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
    public function forecast(array $data): array
    {
        return Sanitize::map($data, [
            PluginSettings::SALES_WINDOW_DAYS => 'int',
            PluginSettings::BATCH_SIZE        => 'int',
            PluginSettings::WARNING_DAYS      => 'int',
            PluginSettings::CRITICAL_DAYS     => 'int',
        ]);
    }

    /**
     * Sanitize all sections at once.
     *
     * @param array $data
     * @return array
     */
    public function all(array $data): array
    {
        return $this->forecast($data);
    }
}
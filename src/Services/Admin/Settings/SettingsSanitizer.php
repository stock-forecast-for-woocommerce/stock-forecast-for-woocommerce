<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\Utils\Sanitize;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitizes plugin settings.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @since   1.0.0
 */
class SettingsSanitizer
{
    /** Sanitizes forecast settings. */
    public function forecast(array $data): array
    {
        return Sanitize::map($data, [
            PluginSettings::SALES_WINDOW_DAYS => 'int',
            PluginSettings::BATCH_SIZE        => 'int',
            PluginSettings::WARNING_DAYS      => 'int',
            PluginSettings::CRITICAL_DAYS     => 'int',
        ]);
    }

    /** Sanitizes all settings sections. */
    public function all(array $data): array
    {
        return $this->forecast($data);
    }
}
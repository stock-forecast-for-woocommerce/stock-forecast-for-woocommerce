<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Utils\OptionUtils;
use StockForecastForWooCommerce\Config\PluginSettings;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SettingsData
 *
 * Provides plugin settings data for the admin settings page.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @version 1.0.0
 */
class SettingsData
{
    /**
     * Get all plugin settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return OptionUtils::getAllOptions();
    }

    /**
     * Get settings sections.
     *
     * @return array
     */
    public function getSections(): array
    {
        $sections = [
            PluginSettings::SECTION_FORECAST => [
                'template' => 'admin/pages/settings/sections/forecast',
            ]
        ];

        /**
         * Filter plugin settings sections.
         *
         * @param array $sections
         */
        return apply_filters(
            'stock_forecast_for_woocommerce_settings_sections',
            $sections
        );
    }
}

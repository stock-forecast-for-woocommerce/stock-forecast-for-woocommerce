<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Config\PrefixConfig;
use StockForecastForWooCommerce\Utils\OptionUtils;
use StockForecastForWooCommerce\Config\PluginSettings;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Provides admin settings data.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @since   1.0.0
 */
class SettingsData
{
    /** Gets all plugin settings. */
    public function getSettings(): array
    {
        return OptionUtils::getAllOptions();
    }

    /** Gets settings sections. */
    public function getSections(): array
    {
        $sections = [
            PluginSettings::SECTION_FORECAST => [
                'template' => 'admin/pages/settings/sections/forecast',
                'title'    => __('Forecasting', 'stock-forecast-for-woocommerce'),
                'icon'     => PrefixConfig::css('icon--chart-line'),
            ]
        ];

        /**
         * Filters plugin settings sections.
         *
         * @param array $sections Settings sections.
         * @since 1.0.0
         */
        return apply_filters(
            'stock_forecast_for_woocommerce_settings_sections',
            $sections
        );
    }
}
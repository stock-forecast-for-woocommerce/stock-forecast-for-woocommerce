<?php

namespace StockForecastForWooCommerce\Services\Admin\Dashboard;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers the plugin dashboard menu.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Dashboard
 * @since   1.0.0
 */
class DashboardManager
{
    /** Register dashboard hooks. */
    public function register(): void
    {
        add_filter('stock_forecast_for_woocommerce_menu_items', [$this, 'addMenuItem']);
    }

    /** Add dashboard menu items. */
    public function addMenuItem(array $items): array
    {
        /**
         * Filters the plugin menu position.
         *
         * @param float $position Default admin menu position.
         * @since 1.0.0
         */
        $position = apply_filters('stock_forecast_for_woocommerce_menu_item_position', 56);

        $items[] = [
            'id'       => 'stock-forecast-for-woocommerce',
            'title'    => __('Stock Forecast', 'stock-forecast-for-woocommerce'),
            'icon'     => 'dashicons-chart-line',
            'position' => $position,
            'callback' => DashboardPage::class,
        ];

        $items[] = [
            'id'       => 'stock-forecast-for-woocommerce',
            'title'    => esc_html__('Dashboard', 'stock-forecast-for-woocommerce'),
            'parentId' => 'stock-forecast-for-woocommerce',
            'callback' => DashboardPage::class,
        ];

        return $items;
    }
}
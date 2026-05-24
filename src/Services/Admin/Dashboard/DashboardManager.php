<?php

namespace StockForecastForWooCommerce\Services\Admin\Dashboard;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DashboardManager
 *
 * Registers the admin dashboard menu for the plugin.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Dashboard
 * @version 1.0.0
 */
class DashboardManager
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('stock_forecast_for_woocommerce_menu_items', [$this, 'addMenuItem']);
    }

    /**
     * Add dashboard menu items.
     *
     * @param array $items
     * @return array
     */
    public function addMenuItem(array $items): array
    {
        /**
         * Apply a filter to determine the position of the plugin menu item.
         *
         * @param float $position The default position of the menu item in the admin menu.
         *
         * @return float The possibly modified position after applying filters.
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
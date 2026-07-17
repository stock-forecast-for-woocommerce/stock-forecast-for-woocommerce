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
        $icon = 'data:image/svg+xml;base64, PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAyMCIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIj4KICA8ZyBmaWxsPSJub25lIiBzdHJva2U9IiNmM2YxZjEiIHN0cm9rZS13aWR0aD0iMi4yIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogICAgCiAgICA8IS0tIFcgd2F0ZXJtYXJrIChwcm9wZXIgb3JpZW50YXRpb24pIC0tPgogICAgPHBhdGggZD0iTTQgNiBMNyAxNCBMMTAgOSBMMTMgMTQgTDE2IDYiIHN0cm9rZS13aWR0aD0iMi41IiBvcGFjaXR5PSIwLjQiLz4KICAgIAogICAgPCEtLSBDdXJ2ZWQgdHJlbmQgbGluZSAtLT4KICAgIDxwYXRoIGQ9Ik00IDE1IFEgNS41IDQgMTcgNSIgc3Ryb2tlLXdpZHRoPSIyIi8+CiAgICAKICAgIDwhLS0gU3RhcnQgZG90IChjdXJyZW50IHN0b2NrKSAtLT4KICAgIDxjaXJjbGUgY3g9IjQiIGN5PSIxNSIgcj0iMS44IiBmaWxsPSIjZjNmMWYxIiBzdHJva2U9Im5vbmUiLz4KICAgIAogICAgPCEtLSBFbmQgZG90IChmb3JlY2FzdCkgLS0+CiAgICA8Y2lyY2xlIGN4PSIxNyIgY3k9IjUiIHI9IjIuMiIgZmlsbD0iI2YzZjFmMSIgc3Ryb2tlPSJub25lIi8+CiAgICAKICA8L2c+Cjwvc3ZnPg==';

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
            'icon'     => $icon,
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
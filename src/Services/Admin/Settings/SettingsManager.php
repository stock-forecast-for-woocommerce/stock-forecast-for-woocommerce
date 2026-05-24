<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SettingsManager
 *
 * Registers the plugin settings admin page.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @version 1.0.0
 */
class SettingsManager
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('stock_forecast_for_woocommerce_menu_items', [$this, 'addMenuItem']);
        add_action('admin_post_sffw_save_settings', [$this, 'handle']);
    }

    /**
     * Add Settings menu item.
     *
     * @param array $items
     * @return array
     */
    public function addMenuItem(array $items): array
    {
        $items[] = [
            'id'       => 'stock-forecast-for-woocommerce-settings',
            'title'    => esc_html__('Settings', 'stock-forecast-for-woocommerce'),
            'parentId' => 'stock-forecast-for-woocommerce',
            'callback' => SettingsPage::class,
        ];

        return $items;
    }

    /**
     * Handle settings save request.
     *
     * @return void
     */
    public function handle(): void
    {
        $settingsSaveHandler = new SettingsSaveHandler();
        $settingsSaveHandler->handle();
    }
}
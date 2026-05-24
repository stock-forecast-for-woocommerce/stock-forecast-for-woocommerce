<?php

namespace StockForecastForWooCommerce\Services\Admin\ProductForecast;

use StockForecastForWooCommerce\Components\AjaxComponent;
use StockForecastForWooCommerce\Queue\QueueManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ProductForecastManager
 *
 * Registers the Product Forecast admin menu page.
 *
 * @package StockForecastForWooCommerce\Services\Admin\ProductForecast
 * @version 1.0.0
 */
class ProductForecastManager
{
    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('stock_forecast_for_woocommerce_menu_items', [$this, 'addMenuItem']);

        // Register AJAX handler for refresh forecasts.
        AjaxComponent::register('refresh_forecasts', [$this, 'refreshForecasts'], false);
    }

    /**
     * Add Product Forecast menu item.
     *
     * @param array $items
     * @return array
     */
    public function addMenuItem(array $items): array
    {
        $items[] = [
            'id'       => 'stock-forecast-for-woocommerce-product-forecast',
            'title'    => esc_html__('Product Forecast', 'stock-forecast-for-woocommerce'),
            'parentId' => 'stock-forecast-for-woocommerce',
            'callback' => ProductForecastPage::class,
        ];

        return $items;
    }

    /**
     * Handle the "refresh_forecasts" AJAX request.
     *
     * Pushes the product-forecast batch job into the queue and returns a
     * standardized JSON success response.
     *
     * @return void
     */
    public function refreshForecasts(): void
    {
        // Safe: Nonce is verified and user capability is checked in AjaxComponent::register().

        QueueManager::instance()->push('forecast_batch_products');

        AjaxComponent::sendSuccess([
            'message' => __('Forecast refresh queued', 'stock-forecast-for-woocommerce'),
        ]);
    }
}
<?php

namespace StockForecastForWooCommerce\Services\Admin\ProductForecast;

use StockForecastForWooCommerce\Components\AjaxComponent;
use StockForecastForWooCommerce\Queue\QueueManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Manages the Product Forecast admin page.
 *
 * @package StockForecastForWooCommerce\Services\Admin\ProductForecast
 * @since   1.0.0
 */
class ProductForecastManager
{
    /** Registers the product forecast hooks. */
    public function register(): void
    {
        add_filter('stock_forecast_for_woocommerce_menu_items', [$this, 'addMenuItem']);

        AjaxComponent::register('refresh_forecasts', [$this, 'refreshForecasts'], false);
    }

    /** Adds the Product Forecast menu item. */
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

    /** Refreshes product forecasts. */
    public function refreshForecasts(): void
    {
        // Safe: Nonce is verified and user capability is checked in AjaxComponent::register().

        QueueManager::instance()->push('forecast_batch_products');

        AjaxComponent::sendSuccess([
            'message' => __('Forecast refresh queued', 'stock-forecast-for-woocommerce'),
        ]);
    }
}
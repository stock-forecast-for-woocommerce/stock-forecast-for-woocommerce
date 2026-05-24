<?php

namespace StockForecastForWooCommerce\Services\Forecast;

use StockForecastForWooCommerce\Config\PluginMeta;
use StockForecastForWooCommerce\Models\Forecast;
use StockForecastForWooCommerce\Queue\QueueManager;
use StockForecastForWooCommerce\Services\Forecast\Jobs\SingleProductForecastJob;
use StockForecastForWooCommerce\Services\Forecast\Jobs\BatchProductForecastJob;
use StockForecastForWooCommerce\Utils\OptionUtils;
use StockForecastForWooCommerce\Services\Forecast\Crons\DailyStaleForecast;
use WC_Product;
use WC_Order;
use WC_Order_Item_Product;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ForecastManager
 *
 * Coordinates forecast jobs and WooCommerce events.
 *
 * @package StockForecastForWooCommerce\Services\Forecast
 * @version 1.0.0
 */
class ForecastManager
{
    /**
     * Register hooks and queue jobs.
     */
    public function register(): void
    {
        add_filter('stock_forecast_for_woocommerce_queue_jobs', [$this, 'registerForecastJobs']);

        add_filter('stock_forecast_for_woocommerce_daily_cron_services', [$this, 'registerDailyCronServices']);

        add_action('init', [$this, 'dispatchInitialForecast']);

        add_action('woocommerce_product_set_stock', [$this, 'dispatchProductForecast']);
        add_action('woocommerce_variation_set_stock', [$this, 'dispatchProductForecast']);

        add_action('woocommerce_order_status_processing', [$this, 'dispatchOrderForecasts']);

        add_action('woocommerce_before_delete_product_variation', [$this, 'onVariationDeleted']);
        add_action('before_delete_post', [$this, 'onProductDeleted']);
    }

    /**
     * Register forecast jobs in the queue system.
     *
     * @param array $jobs
     * @return array
     */
    public function registerForecastJobs(array $jobs): array
    {
        $forecastJobs = [
            SingleProductForecastJob::class,
            BatchProductForecastJob::class,
        ];

        return array_merge($jobs, $forecastJobs);
    }

    /**
     * Registers daily cron services by merging them with the existing service list.
     *
     * @param array $services
     * @return array
     */
    public function registerDailyCronServices(array $services): array
    {
        $dailyCronServices = [
            DailyStaleForecast::class
        ];

        return array_merge($services, $dailyCronServices);
    }

    /**
     * Dispatch the initial full forecast job.
     *
     * @return void
     */
    public function dispatchInitialForecast(): void
    {
        $isInitialForecastQueued = OptionUtils::getMeta(PluginMeta::INITIAL_FORECAST_DISPATCHED);

        if ($isInitialForecastQueued === 'yes') {
            return;
        }

        QueueManager::instance()->push('forecast_batch_products');

        OptionUtils::setMeta(PluginMeta::INITIAL_FORECAST_DISPATCHED, 'yes');
    }

    /**
     * Dispatch forecast job for a single product.
     *
     * @param WC_Product|int $product
     */
    public function dispatchProductForecast($product): void
    {
        $productObj = $product instanceof WC_Product ? $product : wc_get_product((int)$product);

        if (!$productObj instanceof WC_Product) {
            return;
        }

        if (!$productObj->is_type(['simple', 'variation'])) {
            return;
        }

        if (!$productObj->managing_stock()) {
            if ($productObj->is_type('variation')) {
                Forecast::deleteByVariationId($productObj->get_id());
            } else {
                Forecast::deleteByProductId($productObj->get_id());
            }

            return;
        }

        $productId = $productObj->is_type('variation')
            ? $productObj->get_parent_id()
            : $productObj->get_id();

        QueueManager::instance()->push(
            'forecast_single_product',
            [
                'product_id' => $productId,
            ]
        );
    }

    /**
     * Dispatch forecast jobs for products in an order.
     *
     * @param int $orderId
     */
    public function dispatchOrderForecasts(int $orderId): void
    {
        $order = wc_get_order($orderId);

        if (!$order instanceof WC_Order) {
            return;
        }

        $productIds = [];

        foreach ($order->get_items() as $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }

            $product = $item->get_product();

            if (!$product instanceof WC_Product) {
                continue;
            }

            if (!in_array($product->get_type(), ['simple', 'variation'], true)) {
                continue;
            }

            $productIds[] = $product->is_type('variation')
                ? $product->get_parent_id()
                : $product->get_id();
        }

        $productIds = array_unique($productIds);

        if (empty($productIds)) {
            return;
        }

        foreach ($productIds as $productId) {
            $this->dispatchProductForecast($productId);
        }
    }

    /**
     * Handle deletion of a WooCommerce variation.
     */
    public function onVariationDeleted(int $variationId): void
    {
        Forecast::deleteByVariationId($variationId);
    }

    /**
     * Handle deletion of a WooCommerce product.
     */
    public function onProductDeleted(int $postId): void
    {
        if (get_post_type($postId) !== 'product') {
            return;
        }

        Forecast::deleteByProductId($postId);
    }
}
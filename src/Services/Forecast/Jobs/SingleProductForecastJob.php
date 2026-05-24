<?php

namespace StockForecastForWooCommerce\Services\Forecast\Jobs;

use StockForecastForWooCommerce\Abstracts\AbstractJob;
use StockForecastForWooCommerce\Config\PluginMeta;
use StockForecastForWooCommerce\Services\Forecast\ForecastEngine;
use StockForecastForWooCommerce\Utils\DateTimeUtils;
use StockForecastForWooCommerce\Utils\OptionUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SingleProductForecastJob
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Jobs
 * @version 1.0.0
 */
class SingleProductForecastJob extends AbstractJob
{

    /**
     * Job name.
     *
     * @var string
     */
    protected string $job = 'forecast_single_product';

    /**
     * Handle job logic.
     *
     * @param array $payload
     * @return void
     */
    public function handle(array $payload): void
    {
        $productId = (int)($payload['product_id'] ?? 0);

        if ($productId <= 0) {
            return;
        }

        $forecastEngine = new ForecastEngine();
        $forecastEngine->processProduct($productId);

        // Update the timestamp of the last forecast update.
        OptionUtils::setMeta(PluginMeta::FORECAST_LAST_UPDATED, DateTimeUtils::timestamp(), false);
    }
}
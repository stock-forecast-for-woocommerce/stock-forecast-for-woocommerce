<?php

namespace StockForecastForWooCommerce\Services\Forecast\Jobs;

use StockForecastForWooCommerce\Abstracts\AbstractJob;
use StockForecastForWooCommerce\Config\PluginMeta;
use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\DataProviders\ForecastDataProvider;
use StockForecastForWooCommerce\Queue\QueueManager;
use StockForecastForWooCommerce\Services\Forecast\ForecastEngine;
use StockForecastForWooCommerce\Sources\WooCommerceSource;
use StockForecastForWooCommerce\Utils\DateTimeUtils;
use StockForecastForWooCommerce\Utils\OptionUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Processes forecast batches in the background.
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Jobs
 * @since   1.0.0
 */
class BatchProductForecastJob extends AbstractJob
{
    /** Job name. */
    protected string $job = 'forecast_batch_products';

    /** Handle the queued job. */
    public function handle(array $payload): void
    {
        $wooCommerceSource    = new WooCommerceSource();
        $forecastDataProvider = new ForecastDataProvider();

        $limit = (int)($payload['limit'] ?? OptionUtils::getOption(
            OptionUtils::makeKey(
                PluginSettings::SECTION_FORECAST,
                PluginSettings::BATCH_SIZE
            ),
            100
        ));

        $afterId       = (int)($payload['after_id'] ?? 0);
        $staleForecast = (bool)($payload['stale_forecast'] ?? false);

        if ($staleForecast) {
            $productIds = $forecastDataProvider->getStaleForecastProductIds($limit, $afterId);
        } else {
            $productIds = $wooCommerceSource->getProductIds($limit, $afterId);
        }

        if (empty($productIds)) {
            return;
        }

        $forecastEngine = new ForecastEngine();
        $forecastEngine->processProducts($productIds);

        if (count($productIds) < $limit) {
            OptionUtils::setMeta(
                PluginMeta::FORECAST_LAST_UPDATED,
                DateTimeUtils::timestamp(),
                false
            );

            return;
        }

        $lastId = (int)end($productIds);

        QueueManager::instance()->push(
            $this->job,
            [
                'limit'          => $limit,
                'after_id'       => $lastId,
                'stale_forecast' => $staleForecast,
            ]
        );
    }
}
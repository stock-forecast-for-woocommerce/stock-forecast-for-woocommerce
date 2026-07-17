<?php

namespace StockForecastForWooCommerce\Services\Forecast\Crons;

use StockForecastForWooCommerce\Cache\CacheKeys;
use StockForecastForWooCommerce\Cache\CacheManager;
use StockForecastForWooCommerce\Queue\QueueManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schedules daily processing of stale forecasts.
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Crons
 * @since   1.0.0
 */
class DailyStaleForecast
{
    /** Run the daily stale forecast process. */
    public function run(): void
    {
        $cache   = CacheManager::instance();
        $lockKey = CacheKeys::dailyLock();

        if ($cache->get($lockKey)) {
            return;
        }

        $cache->set($lockKey, 1, MINUTE_IN_SECONDS * 5);

        QueueManager::instance()->push(
            'forecast_batch_products',
            [
                'stale_forecast' => true,
            ]
        );
    }
}
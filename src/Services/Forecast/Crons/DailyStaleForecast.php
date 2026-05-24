<?php

namespace StockForecastForWooCommerce\Services\Forecast\Crons;

use StockForecastForWooCommerce\Queue\QueueManager;
use StockForecastForWooCommerce\Cache\CacheManager;
use StockForecastForWooCommerce\Cache\CacheKeys;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DailyStaleForecast
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Crons
 * @version 1.0.0
 */
class DailyStaleForecast
{
    /**
     * Executes the daily forecast batch processing if not already locked.
     *
     * @return void
     */
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
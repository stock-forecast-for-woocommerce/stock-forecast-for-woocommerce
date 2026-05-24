<?php

namespace StockForecastForWooCommerce\Config;


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PluginSettings
 *
 * @package StockForecastForWooCommerce\Config
 * @version 1.0.0
 */
final class PluginSettings
{
    /**
     * Forecast settings section.
     */
    public const SECTION_FORECAST = 'forecast';

    /*
    |--------------------------------------------------------------------------
    | Forecast Settings
    |--------------------------------------------------------------------------
    */

    /**
     * Sales analysis window (days).
     */
    public const SALES_WINDOW_DAYS = 'sales_window_days';

    /**
     * Product batch processing size.
     */
    public const BATCH_SIZE = 'batch_size';

    /**
     * Low stock threshold (days).
     */
    public const WARNING_DAYS = 'warning_days';

    /**
     * Critical stock threshold (days).
     */
    public const CRITICAL_DAYS = 'critical_days';
}
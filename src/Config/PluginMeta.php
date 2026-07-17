<?php

namespace StockForecastForWooCommerce\Config;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin metadata keys.
 *
 * @package StockForecastForWooCommerce\Config
 * @since   1.0.0
 */
class PluginMeta
{
    /** Current plugin version option key. */
    public const VERSION = 'version';

    /** Database schema version option key. */
    public const DB_VERSION = 'db_version';

    /** Flag to check if the initial product forecast has been queued. */
    public const INITIAL_FORECAST_DISPATCHED = 'initial_forecast_dispatched';

    /** Last forecast update timestamp. */
    public const FORECAST_LAST_UPDATED = 'forecast_last_updated';
}
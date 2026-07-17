<?php

/**
 * Plugin Name:         Stock Forecast for WooCommerce
 * Plugin URI:          https://github.com/stock-forecast-for-woocommerce/stock-forecast-for-woocommerce
 * Description:         Predict stockout dates before they cost you sales. Real‑time forecasts, color‑coded risk levels, and variable product support — no inventory changes.
 * Version:             1.0.0
 * Author:              Mohammad Yari
 * Author URI:          https://github.com/stock-forecast-for-woocommerce
 * Text Domain:         stock-forecast-for-woocommerce
 * Domain Path:         /languages
 * Requires at least:   6.1
 * Requires PHP:        7.4
 * Requires Plugins:    woocommerce
 * License:             GPL-2.0-or-later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 */

# Exit if accessed directly
defined('ABSPATH') || exit;

/** Plugin slug. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_SLUG')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_SLUG', 'stock-forecast-for-woocommerce');
}

/** Main plugin file. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE', __FILE__);
}

/** Main plugin path. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_PATH')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_PATH', plugin_dir_path(STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE));
}

/** Plugin URL. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_URL')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_URL', plugin_dir_url(STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE));
}

/** Default plugin templates path. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_TEMPLATES_PATH')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_TEMPLATES_PATH', STOCK_FORECAST_FOR_WOOCOMMERCE_PATH . 'templates/');
}

/** Plugin assets URL. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_ASSETS')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_ASSETS', STOCK_FORECAST_FOR_WOOCOMMERCE_URL . 'assets/');
}

/** Plugin version. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_VERSION')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_VERSION', '1.0.0');
}

/** Database schema version. */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_DB_VERSION')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_DB_VERSION', '1.0.0');
}

/** Autoload all classes using Composer autoloader. */
require_once __DIR__ . '/vendor/autoload.php';

use StockForecastForWooCommerce\Core;
use StockForecastForWooCommerce\Lifecycle\ActivationHandler;
use StockForecastForWooCommerce\Lifecycle\DeactivationHandler;

/** Register activation hook. */
ActivationHandler::register();

/** Register deactivation hook. */
DeactivationHandler::register();

/** Returns the main instance of the plugin Core class. */
if (!function_exists('stockForecastForWooCommerce')) {
    function stockForecastForWooCommerce(): ?Core
    {
        return Core::instance();
    }
}

/** Initialize the plugin. */
stockForecastForWooCommerce();

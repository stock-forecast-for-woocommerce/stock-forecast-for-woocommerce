<?php

/**
 * Plugin Name:         Stock Forecast for WooCommerce
 * Plugin URI:          https://github.com/stock-forecast-for-woocommerce/stock-forecast-for-woocommerce
 * Description:         Predicts WooCommerce stockout dates using sales velocity. Real-time forecasts, color-coded risk levels, and variable product support. No inventory changes.
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

/**
 * Plugin slug.
 *
 * Used for text domains, options, or unique identifiers.
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_SLUG')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_SLUG', 'stock-forecast-for-woocommerce');
}

/**
 * Main plugin file.
 *
 * Stores the absolute path to the plugin's main file.
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE', __FILE__);
}

/**
 * Main plugin path.
 *
 * Absolute path to the plugin root folder.
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_PATH')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_PATH', plugin_dir_path(STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE));
}

/**
 * Plugin URL.
 *
 * Stores the absolute URL to the plugin folder.
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_URL')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_URL', plugin_dir_url(STOCK_FORECAST_FOR_WOOCOMMERCE_PLUGIN_FILE));
}

/**
 * Default plugin templates path.
 *
 * Absolute path to the plugin templates folder.
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_TEMPLATES_PATH')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_TEMPLATES_PATH', STOCK_FORECAST_FOR_WOOCOMMERCE_PATH . 'templates/');
}

/**
 * Plugin assets URL.
 *
 * Stores the URL to the plugin's assets folder (CSS, JS, images, etc.).
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_ASSETS')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_ASSETS', STOCK_FORECAST_FOR_WOOCOMMERCE_URL . 'assets/');
}

/**
 * Plugin version.
 *
 * Used for cache-busting scripts/styles and version checks.
 *
 * @const string
 */
if (!defined('STOCK_FORECAST_FOR_WOOCOMMERCE_VERSION')) {
    define('STOCK_FORECAST_FOR_WOOCOMMERCE_VERSION', '1.0.0');
}

/**
 * Autoload all classes using Composer autoloader.
 */
require_once __DIR__ . '/vendor/autoload.php';

use StockForecastForWooCommerce\Core;
use StockForecastForWooCommerce\Lifecycle\ActivationHandler;
use StockForecastForWooCommerce\Lifecycle\DeactivationHandler;

/**
 * Register activation hook.
 *
 * Handles plugin activation tasks like creating database tables,
 * setting default options, and flushing rewrite rules.
 */
ActivationHandler::register();

/**
 * Register deactivation hook.
 *
 * Handles plugin deactivation tasks like clearing scheduled events
 * and cleaning up transients.
 */
DeactivationHandler::register();

/**
 * Returns the main instance of the Starter Plugin Core class.
 *
 * Acts as a helper function to access the singleton instance anywhere in the plugin.
 *
 * @return Core Main plugin instance.
 */
if (!function_exists('stockForecastForWooCommerce')) {
    function stockForecastForWooCommerce(): ?Core
    {
        return Core::instance();
    }
}

/**
 * Initialize the plugin by creating the main instance.
 *
 * The actual init logic should be handled inside Core class.
 */
stockForecastForWooCommerce();

<?php

/**
 * Plugin uninstall script.
 *
 * Delegates all cleanup to the UninstallHandler class.
 *
 * @package StockForecastForWooCommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/** Load autoloader. */
require_once __DIR__ . '/vendor/autoload.php';

/** Run uninstall handler. */
StockForecastForWooCommerce\Lifecycle\UninstallHandler::uninstall();
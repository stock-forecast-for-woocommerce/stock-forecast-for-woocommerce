<?php
/**
 * Plugin Uninstall Script
 *
 * This file is executed when the plugin is deleted via WordPress admin.
 * It delegates all cleanup to the UninstallHandler class.
 *
 * IMPORTANT:
 * Do not access this file directly. WordPress defines the constant
 * WP_UNINSTALL_PLUGIN when executing this file.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Exit if not called by WordPress uninstall
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Run uninstall handler
StockForecastForWooCommerce\Lifecycle\UninstallHandler::uninstall();

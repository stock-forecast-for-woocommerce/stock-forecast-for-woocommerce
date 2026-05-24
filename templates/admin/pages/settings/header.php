<?php

/**
 * Template part: Settings page header.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

use StockForecastForWooCommerce\Utils\TemplateUtils;

?>

<!-- Page Header -->
<div class="sffw-page-header">
    <div class="sffw-title-description-wrapper">
        <h1 class="sffw-page-title">
            <?php esc_html_e('Settings', 'stock-forecast-for-woocommerce'); ?>
        </h1>
        <p class="sffw-page-description">
            <?php esc_html_e('Configure how stock forecasts are calculated based on past sales data.', 'stock-forecast-for-woocommerce'); ?>
        </p>
    </div>
</div>
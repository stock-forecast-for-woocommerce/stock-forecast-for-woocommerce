<?php

/**
 * Template part: Product Forecast page header.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var string $forecastLastUpdatedDisplay
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Page Header -->
<div class="sffw-page-header">
    <div class="sffw-title-description-wrapper">
        <h1 class="sffw-page-title">
            <?php esc_html_e('Product Forecast', 'stock-forecast-for-woocommerce'); ?>
        </h1>
        <p class="sffw-page-description">
            <?php esc_html_e('View stock forecasts for each product based on past sales.', 'stock-forecast-for-woocommerce'); ?>
        </p>
        <p class="sffw-page-meta">
            <span class="sffw-icon--clock"></span>
            <?php echo esc_html($forecastLastUpdatedDisplay); ?>
        </p>
    </div>

    <div class="sffw-actions-wrapper">
        <button
            type="button"
            id="sffw-refresh-forecasts"
            class="sffw-refresh-forecasts sffw-btn sffw-btn-outline-secondary"
            aria-label="<?php esc_attr_e('Refresh Forecasts', 'stock-forecast-for-woocommerce'); ?>"
            title="<?php esc_attr_e('Manual forecast recalculation for all products, runs in the background.', 'stock-forecast-for-woocommerce'); ?>"
        >
            <span class="sffw-icon--refresh"></span>
            <span class="sffw-refresh-forecasts-text"><?php esc_html_e('Refresh Forecasts', 'stock-forecast-for-woocommerce'); ?></span>
        </button>
    </div>
</div>
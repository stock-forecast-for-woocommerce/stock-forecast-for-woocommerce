<?php

/**
 * Template part: Dashboard page header.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var string $forecastLastUpdatedDisplay
 * @var string $forecastUpdateLink
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Page Header -->
<div class="sffw-page-header">
    <div class="sffw-title-description-wrapper">
        <h1 class="sffw-page-title">
            <?php esc_html_e('Dashboard', 'stock-forecast-for-woocommerce'); ?>
        </h1>
        <p class="sffw-page-description">
            <?php esc_html_e('Overview of predicted stock levels and potential stock shortages.', 'stock-forecast-for-woocommerce'); ?>
        </p>
        <p class="sffw-page-meta">
            <span class="sffw-icon--clock"></span>
            <?php echo esc_html($forecastLastUpdatedDisplay); ?>
            ·
            <a href="<?php echo esc_url($forecastUpdateLink); ?>" class="sffw-text-secondary" title="<?php esc_attr_e('Go to the forecast page to run full recalculation manually', 'stock-forecast-for-woocommerce'); ?>"><?php esc_html_e('View forecasts →', 'stock-forecast-for-woocommerce'); ?></a>
        </p>
    </div>
</div>
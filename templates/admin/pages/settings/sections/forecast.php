<?php

/**
 * Section part: Forecast Settings.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var array $settings
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="sffw-settings__section">
    <div class="sffw-card">
        <div class="sffw-card-header">
            <div class="sffw-card-header-content">
                <h5 class="sffw-card-title">
                    <?php esc_html_e('Stock Forecast Settings', 'stock-forecast-for-woocommerce'); ?>
                </h5>
                <p class="sffw-card-subtitle">
                    <?php esc_html_e('Configure how sales data is analyzed to estimate future demand and identify products that may run out of stock.', 'stock-forecast-for-woocommerce'); ?>
                </p>
            </div>
        </div>

        <div class="sffw-card-body">
            <div class="sffw-settings__list">

                <div class="sffw-settings__item">
                    <div class="sffw-settings__label">
                        <label for="sffw-sales-window-days" class="sffw-form-label">
                            <?php esc_html_e('Sales History Period (Days)', 'stock-forecast-for-woocommerce'); ?>
                        </label>
                    </div>

                    <div class="sffw-settings__field">
                        <input
                            type="text"
                            name="settings[forecast][sales_window_days]"
                            placeholder="<?php esc_attr_e('e.g. 30', 'stock-forecast-for-woocommerce'); ?>"
                            class="sffw-form-control"
                            id="sffw-sales-window-days"
                            value="<?php echo esc_attr($settings['forecast']['sales_window_days']); ?>"
                        >

                        <div class="sffw-settings__text sffw-form-text">
                            <?php esc_html_e('Number of past days used to calculate the average daily sales for forecasting.', 'stock-forecast-for-woocommerce'); ?>
                        </div>
                    </div>
                </div>

                <div class="sffw-settings__item">
                    <div class="sffw-settings__label">
                        <label for="sffw-batch-size" class="sffw-form-label">
                            <?php esc_html_e('Processing Batch Size', 'stock-forecast-for-woocommerce'); ?>
                        </label>
                    </div>

                    <div class="sffw-settings__field">
                        <input
                            type="text"
                            name="settings[forecast][batch_size]"
                            placeholder="<?php esc_attr_e('e.g. 50', 'stock-forecast-for-woocommerce'); ?>"
                            class="sffw-form-control"
                            id="sffw-batch-size"
                            value="<?php echo esc_attr($settings['forecast']['batch_size']); ?>"
                        >

                        <div class="sffw-settings__text sffw-form-text">
                            <?php esc_html_e('Number of products processed per batch when generating forecasts.', 'stock-forecast-for-woocommerce'); ?>
                        </div>
                    </div>
                </div>

                <div class="sffw-settings__item">
                    <div class="sffw-settings__label">
                        <label for="sffw-warning-days" class="sffw-form-label">
                            <?php esc_html_e('Low Stock Warning Threshold (Days)', 'stock-forecast-for-woocommerce'); ?>
                        </label>
                    </div>

                    <div class="sffw-settings__field">
                        <input
                            type="text"
                            name="settings[forecast][warning_days]"
                            placeholder="<?php esc_attr_e('e.g. 14', 'stock-forecast-for-woocommerce'); ?>"
                            class="sffw-form-control"
                            id="sffw-warning-days"
                            value="<?php echo esc_attr($settings['forecast']['warning_days']); ?>"
                        >

                        <div class="sffw-settings__text sffw-form-text">
                            <?php esc_html_e('Show a warning when the estimated stock will last fewer than this number of days.', 'stock-forecast-for-woocommerce'); ?>
                        </div>
                    </div>
                </div>

                <div class="sffw-settings__item">
                    <div class="sffw-settings__label">
                        <label for="sffw-critical-days" class="sffw-form-label">
                            <?php esc_html_e('Critical Stock Threshold (Days)', 'stock-forecast-for-woocommerce'); ?>
                        </label>
                    </div>

                    <div class="sffw-settings__field">
                        <input
                            type="text"
                            name="settings[forecast][critical_days]"
                            placeholder="<?php esc_attr_e('e.g. 7', 'stock-forecast-for-woocommerce'); ?>"
                            class="sffw-form-control"
                            id="sffw-critical-days"
                            value="<?php echo esc_attr($settings['forecast']['critical_days']); ?>"
                        >

                        <div class="sffw-settings__text sffw-form-text">
                            <?php esc_html_e('Mark products as critical when the estimated stock coverage falls below this level.', 'stock-forecast-for-woocommerce'); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
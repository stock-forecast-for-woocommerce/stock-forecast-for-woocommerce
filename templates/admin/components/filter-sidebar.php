<?php
/**
 * Component: Filter Sidebar.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="sffw-filter-sidebar" class="sffw-filter-sidebar">

    <div class="sffw-filter-sidebar__overlay"></div>

    <aside class="sffw-filter-sidebar__panel" aria-label="<?php esc_attr_e('Forecast filters', 'stock-forecast-for-woocommerce'); ?>">

        <!-- Header -->
        <div class="sffw-filter-sidebar__header">

            <h3 class="sffw-filter-sidebar__title">
                <?php esc_html_e('Advanced Filters', 'stock-forecast-for-woocommerce'); ?>
            </h3>

            <button
                type="button"
                class="sffw-filter-sidebar__close"
                aria-label="<?php esc_attr_e('Close filters', 'stock-forecast-for-woocommerce'); ?>"
            >
                <span class="sffw-icon--close"></span>
            </button>

        </div>

        <!-- Filters -->
        <form
            id="sffw-forecast-filters"
            class="sffw-filter-sidebar__form"
        >

            <!-- Body -->
            <div class="sffw-filter-sidebar__body">

                <!-- Stock Risk -->
                <div class="sffw-filter-sidebar__group">

                    <div class="sffw-filter-sidebar__group-title">
                        <?php esc_html_e('Stock Risk', 'stock-forecast-for-woocommerce'); ?>
                    </div>

                    <div class="sffw-filter-sidebar__field">
                        <label for="sffw-forecast-risk-level" class="sffw-form-label">
                            <?php esc_html_e('Stockout Risk', 'stock-forecast-for-woocommerce'); ?>
                        </label>

                        <select
                            name="risk_level"
                            id="sffw-forecast-risk-level"
                            class="sffw-form-select"
                        >
                            <option value=""><?php esc_html_e('All Levels', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="safe"><?php esc_html_e('Safe Stock', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="warning"><?php esc_html_e('Low Stock', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="critical"><?php esc_html_e('Critical Stock', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="out_of_stock"><?php esc_html_e('Out of Stock', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="backordering"><?php esc_html_e('Backorders', 'stock-forecast-for-woocommerce'); ?></option>
                        </select>
                    </div>

                    <div class="sffw-filter-sidebar__field">
                        <label for="sffw-forecast-days-until-stockout" class="sffw-form-label">
                            <?php esc_html_e('Stockout Within (Days)', 'stock-forecast-for-woocommerce'); ?>
                        </label>

                        <select
                            name="days_until_stockout"
                            id="sffw-forecast-days-until-stockout"
                            class="sffw-form-select"
                        >
                            <option value=""><?php esc_html_e('Any', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="7"><?php esc_html_e('≤ 7 days', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="14"><?php esc_html_e('≤ 14 days', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="30"><?php esc_html_e('≤ 30 days', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="60"><?php esc_html_e('≤ 60 days', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="90"><?php esc_html_e('≤ 90 days', 'stock-forecast-for-woocommerce'); ?></option>
                        </select>
                    </div>

                </div>

                <!-- Product -->
                <div class="sffw-filter-sidebar__group">

                    <div class="sffw-filter-sidebar__group-title">
                        <?php esc_html_e('Product', 'stock-forecast-for-woocommerce'); ?>
                    </div>

                    <div class="sffw-filter-sidebar__field">
                        <label for="sffw-forecast-product-type" class="sffw-form-label">
                            <?php esc_html_e('Product Type', 'stock-forecast-for-woocommerce'); ?>
                        </label>

                        <select
                            name="product_type"
                            id="sffw-forecast-product-type"
                            class="sffw-form-select"
                        >
                            <option value=""><?php esc_html_e('All Types', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="simple"><?php esc_html_e('Simple', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="variation"><?php esc_html_e('Variation', 'stock-forecast-for-woocommerce'); ?></option>
                        </select>
                    </div>

                </div>

                <!-- Inventory & Sales -->
                <div class="sffw-filter-sidebar__group">

                    <div class="sffw-filter-sidebar__group-title">
                        <?php esc_html_e('Inventory & Sales', 'stock-forecast-for-woocommerce'); ?>
                    </div>

                    <div class="sffw-filter-sidebar__field">
                        <label for="sffw-forecast-current-stock" class="sffw-form-label">
                            <?php esc_html_e('Current Stock', 'stock-forecast-for-woocommerce'); ?>
                        </label>

                        <select
                            name="current_stock"
                            id="sffw-forecast-current-stock"
                            class="sffw-form-select"
                        >
                            <option value=""><?php esc_html_e('Any', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="0">0</option>
                            <option value="range:1-10">1–10</option>
                            <option value="range:11-50">11–50</option>
                            <option value="range:51-100">51–100</option>
                            <option value="gte:100">100+</option>
                        </select>
                    </div>

                    <div class="sffw-filter-sidebar__field">
                        <label for="sffw-forecast-daily-sales" class="sffw-form-label">
                            <?php esc_html_e('Daily Sales', 'stock-forecast-for-woocommerce'); ?>
                        </label>

                        <select
                            name="daily_sales"
                            id="sffw-forecast-daily-sales"
                            class="sffw-form-select"
                        >
                            <option value=""><?php esc_html_e('Any', 'stock-forecast-for-woocommerce'); ?></option>
                            <option value="0">0</option>
                            <option value="range:1-5">1–5</option>
                            <option value="range:6-20">6–20</option>
                            <option value="range:21-50">21–50</option>
                            <option value="gte:50">50+</option>

                        </select>
                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="sffw-filter-sidebar__footer">

                <button
                    type="button"
                    id="sffw-filter-reset"
                    class="sffw-btn sffw-btn-secondary"
                >
                    <?php esc_html_e('Reset', 'stock-forecast-for-woocommerce'); ?>
                </button>

                <button
                    type="submit"
                    class="sffw-btn sffw-btn-primary"
                >
                    <?php esc_html_e('Apply Filters', 'stock-forecast-for-woocommerce'); ?>
                </button>

            </div>

        </form>

    </aside>

</div>
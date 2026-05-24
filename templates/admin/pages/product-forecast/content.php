<?php

/**
 * Template part: Product Forecast page content.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var array $forecastsData
 */

if (!defined('ABSPATH')) {
    exit;
}

use StockForecastForWooCommerce\Utils\TemplateUtils;

?>
    <div class="sffw-page-content">
        <!-- Product Forecast -->
        <div class="sffw-section">
            <div class="sffw-row sffw-mb-2 sffw-align-items-center sffw-justify-content-between">
                <div class="sffw-col-12 sffw-col-sm-auto sffw-d-flex sffw-gap-2">
                    <div class="sffw-input-group sffw-input-group-merge sffw-search-group">
                    <span class="sffw-input-group-text">
                        <span class="sffw-icon--search"></span>
                    </span>
                        <input
                            type="search"
                            id="sffw-forecast-search"
                            name="search"
                            class="sffw-form-control"
                            placeholder="<?php esc_attr_e('Product name or SKU…', 'stock-forecast-for-woocommerce'); ?>"
                            value="<?php echo esc_attr($forecastsData['filters']['search']); ?>"
                        >
                    </div>

                    <button
                        type="button"
                        id="sffw-filter-toggle"
                        class="sffw-filter-toggle sffw-btn sffw-btn-secondary sffw-d-none sffw-d-sm-inline-flex"
                        aria-label="<?php esc_attr_e('Advanced Filters', 'stock-forecast-for-woocommerce'); ?>"
                        aria-controls="sffw-filter-sidebar"
                    >
                        <span class="sffw-icon--filter-search"></span>
                        <?php esc_html_e('Advanced Filters', 'stock-forecast-for-woocommerce'); ?>

                        <span
                            class="sffw-filter-count"
                            data-sffw-count="0"
                            aria-hidden="true"
                        ></span>
                    </button>
                </div>
                <div class="sffw-col-12 sffw-col-sm-auto">
                    <!-- Pagination -->
                    <?php if ($forecastsData['pagination']['pages'] > 1): ?>
                        <?php
                        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
                        echo TemplateUtils::renderTemplate(
                            'admin/components/pagination',
                            [
                                'current' => $forecastsData['pagination']['current'],
                                'perPage' => $forecastsData['pagination']['perPage'],
                                'total'   => $forecastsData['pagination']['total'],
                                'pages'   => $forecastsData['pagination']['pages'],
                            ]
                        );
                        // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Active Filter Chips -->
            <div id="sffw-active-filters" class="sffw-active-filters sffw-d-none sffw-d-sm-flex"></div>

            <?php if ($forecastsData['hasData']): ?>
                <div class="sffw-card sffw-table-card ">
                    <div class="sffw-table-responsive">
                        <?php
                        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
                        echo TemplateUtils::renderTemplate(
                            'admin/components/table',
                            [
                                'columns' => $forecastsData['columns'],
                                'rows'    => $forecastsData['rows'],
                                'class'   => $forecastsData['class'],
                            ]);
                        // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </div>
                </div>
            <?php else: ?>
                <?php
                // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
                echo TemplateUtils::renderTemplate(
                    'admin/components/message-card',
                    [
                        'title' => $forecastsData['title'],
                        'text'  => $forecastsData['text'],
                        'icon'  => $forecastsData['icon'],
                        'color' => $forecastsData['color'],
                    ]);
                // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            <?php endif; ?>

            <div class="sffw-row sffw-mt-2 sffw-align-items-center sffw-justify-content-between">

                <!-- Rows per page -->
                <div class="sffw-col-12 sffw-col-sm-auto">
                    <div class="sffw-per-page sffw-d-flex sffw-align-items-center sffw-gap-2 sffw-flex-nowrap">
                        <label for="sffw-filter-per-page" class="sffw-form-label sffw-text-nowrap">
                            <?php esc_html_e('Rows per page:', 'stock-forecast-for-woocommerce'); ?>
                        </label>

                        <select
                            id="sffw-filter-per-page"
                            name="per_page"
                            class="sffw-form-select"
                        >
                            <option value="10" <?php selected($forecastsData['pagination']['perPage'], 10); ?>>10</option>
                            <option value="20" <?php selected($forecastsData['pagination']['perPage'], 20); ?>>20</option>
                            <option value="50" <?php selected($forecastsData['pagination']['perPage'], 50); ?>>50</option>
                            <option value="100" <?php selected($forecastsData['pagination']['perPage'], 100); ?>>100</option>
                        </select>
                    </div>
                </div>

                <div class="sffw-col-12 sffw-col-sm-auto">
                    <!-- Pagination -->
                    <?php if ($forecastsData['pagination']['pages'] > 1): ?>
                        <?php
                        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo TemplateUtils::renderTemplate(
                            'admin/components/pagination',
                            [
                                'current' => $forecastsData['pagination']['current'],
                                'perPage' => $forecastsData['pagination']['perPage'],
                                'total'   => $forecastsData['pagination']['total'],
                                'pages'   => $forecastsData['pagination']['pages'],
                            ]
                        );
                        // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
echo TemplateUtils::renderTemplate(
    'admin/components/filter-sidebar',
    []
);
// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
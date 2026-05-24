<?php

/**
 * Template part: Dashboard page content.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var array $stats
 * @var array $criticalProductsData
 */

if (!defined('ABSPATH')) {
    exit;
}

use StockForecastForWooCommerce\Utils\TemplateUtils;

?>
<div class="sffw-page-content">
    <!--  Inventory Health Overview  -->
    <div class="sffw-section">
        <h3 class="sffw-section__title"><?php esc_html_e('Inventory Health Overview', 'stock-forecast-for-woocommerce'); ?></h3>
        <p class="sffw-section__description"><?php esc_html_e('Key metrics showing the current state of your product inventory.', 'stock-forecast-for-woocommerce'); ?></p>
        <div class="sffw-row">
            <?php foreach ($stats as $stat) : ?>
                <div class="sffw-col-12 sffw-col-sm-6 sffw-col-md-4 sffw-col-lg-3 sffw-col-xxl-2">
                    <?php
                    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
                    echo TemplateUtils::renderTemplate('admin/components/stat-card', [
                        'icon'     => $stat['icon'],
                        'label'    => $stat['label'],
                        'subtitle' => $stat['subtitle'],
                        'value'    => $stat['value'],
                        'color'    => $stat['color'],
                        'link'     => $stat['link'],
                    ]);
                    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!--  Critical Products  -->
    <div class="sffw-section">
        <h3 class="sffw-section__title"><?php esc_html_e('Critical Products', 'stock-forecast-for-woocommerce'); ?></h3>
        <p class="sffw-section__description"><?php esc_html_e('Products at highest risk of stockout based on current inventory and sales velocity.', 'stock-forecast-for-woocommerce'); ?></p>
        <?php if ($criticalProductsData['hasData']): ?>
            <div class="sffw-card sffw-table-card ">
                <div class="sffw-table-responsive">
                    <?php
                    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
                    echo TemplateUtils::renderTemplate('admin/components/table', [
                        'columns' => $criticalProductsData['columns'],
                        'rows'    => $criticalProductsData['rows'],
                        'class'   => $criticalProductsData['class'],
                    ]);
                    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>
            </div>
        <?php else: ?>
            <?php
            // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
            echo TemplateUtils::renderTemplate('admin/components/message-card', [
                'title' => $criticalProductsData['title'],
                'text'  => $criticalProductsData['text'],
                'icon'  => $criticalProductsData['icon'],
                'color' => $criticalProductsData['color'],
            ]);
            // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        <?php endif; ?>
    </div>
</div>
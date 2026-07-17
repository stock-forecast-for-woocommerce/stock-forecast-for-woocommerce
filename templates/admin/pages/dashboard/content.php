<?php

/**
 * Template part: Dashboard page content.
 *
 * All output is captured by TemplateUtils::renderTemplate() and
 * escaped late via wp_kses() in AbstractAdminPage::render().
 *
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce\Templates\Admin\Pages\Dashboard
 * @since   1.0.0
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
                    echo TemplateUtils::renderTemplate('admin/components/stat-card', [
                        'icon'     => $stat['icon'],
                        'label'    => $stat['label'],
                        'subtitle' => $stat['subtitle'],
                        'value'    => $stat['value'],
                        'color'    => $stat['color'],
                        'link'     => $stat['link'],
                    ]);
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
                    echo TemplateUtils::renderTemplate('admin/components/table', [
                        'columns' => $criticalProductsData['columns'],
                        'rows'    => $criticalProductsData['rows'],
                        'class'   => $criticalProductsData['class'],
                    ]);
                    ?>
                </div>
            </div>
        <?php else: ?>
            <?php
            echo TemplateUtils::renderTemplate('admin/components/message-card', [
                'title' => $criticalProductsData['title'],
                'text'  => $criticalProductsData['text'],
                'icon'  => $criticalProductsData['icon'],
                'color' => $criticalProductsData['color'],
            ]);
            ?>
        <?php endif; ?>
    </div>
</div>
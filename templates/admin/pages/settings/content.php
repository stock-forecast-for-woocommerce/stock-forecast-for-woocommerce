<?php

/**
 * Template part: Settings page content.
 *
 * All output is captured by TemplateUtils::renderTemplate() and
 * escaped late via wp_kses() in AbstractAdminPage::render().
 *
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var array $sections
 * @var array $settings
 * @var string $nonceAction
 * @var string $nonceName
 * @var string $formAction
 * @var string $formName
 */

if (!defined('ABSPATH')) {
    exit;
}

use StockForecastForWooCommerce\Utils\TemplateUtils;

?>
<div class="sffw-page-content">
    <div class="sffw-settings-panel">
        <!-- Sidebar: vertical menu -->
        <div class="sffw-settings-sidebar">
            <div class="sffw-settings-nav">
                <?php foreach ($sections as $sectionId => $section) : ?>
                    <button
                        type="button"
                        class="sffw-nav-item"
                        data-sffw-section="<?php echo esc_attr($sectionId); ?>"
                    >
                        <span class="sffw-nav-item__icon <?php echo esc_attr($section['icon']); ?>"></span>
                        <span class="sffw-nav-item__text"><?php echo esc_html($section['title']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="sffw-section">
            <div class="sffw-settings">
                <form method="post" action="<?php echo esc_url($formAction); ?>" class="sffw-settings-form">
                    <?php
                    foreach ($sections as $sectionId => $section) {
                        echo TemplateUtils::renderTemplate(
                            $section['template'],
                            [
                                'sectionId' => $sectionId,
                                'section'   => $section,
                                'settings'  => $settings,
                            ]
                        );
                    }
                    ?>

                    <p>
                        <?php wp_nonce_field($nonceAction, $nonceName); ?>
                        <input type="hidden" name="action" value="<?php echo esc_attr($formName); ?>">
                        <button
                            class="sffw-btn sffw-btn-primary"
                            type="submit"
                        >
                            <?php esc_html_e('Save Changes', 'stock-forecast-for-woocommerce'); ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
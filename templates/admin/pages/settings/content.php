<?php

/**
 * Template part: Settings page content.
 *
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
    <div class="sffw-section">
        <div class="sffw-settings">
            <form method="post" action="<?php echo esc_url($formAction); ?>" class="sffw-settings-form">
                <?php
                // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Output escaped in template
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
                // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
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
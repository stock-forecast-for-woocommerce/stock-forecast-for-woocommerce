<?php
/**
 * Component: Message card.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var string $title Card title (e.g., 'Getting started', 'All clear')
 * @var string $text Main message text
 * @var string $helper Optional helper/tip text displayed below main text
 * @var string $icon Optional icon class (default: 'sffw-icon--info')
 * @var string $color Color variant: 'info', 'success', 'warning' (default: 'info')
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="sffw-card sffw-message-card sffw-message-card--<?php echo esc_attr($color); ?>">
    <div class="sffw-message-card__body">
        <div class="sffw-message-card__icon">
            <span class="<?php echo esc_attr($icon); ?>"></span>
        </div>
        <div class="sffw-message-card__content">
            <h5 class="sffw-message-card__title"><?php echo esc_html($title); ?></h5>
            <p class="sffw-message-card__text"><?php echo esc_html($text); ?></p>
            <?php if (!empty($helper)) : ?>
                <p class="sffw-message-card__helper"><?php echo esc_html($helper); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

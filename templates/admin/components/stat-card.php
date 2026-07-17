<?php

/**
 * Component: Stat card.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce\Templates\Admin\Components
 * @since   1.0.0
 *
 * @var string $icon Icon CSS class
 * @var string|int $value Stat value
 * @var string $label Stat label
 * @var string $subtitle Optional subtitle
 * @var string $color Icon color variant
 * @var string $link Card destination URL
 */

if (!defined('ABSPATH')) {
    exit;
}

$tag   = $link ? 'a' : 'div';
$attrs = $link ? 'href=' . esc_url($link) : '';
?>
<<?php echo esc_html($tag); ?> class="sffw-stat-card" <?php echo esc_attr($attrs); ?>>
<div class="sffw-stat-card__body">

    <div class="sffw-stat-card__icon sffw-stat-card__icon--<?php echo esc_attr($color); ?>">
        <span class="<?php echo esc_attr($icon); ?>"></span>
    </div>

    <div class="sffw-stat-card__content">
        <p class="sffw-stat-card__label"><?php echo esc_html($label); ?></p>
        <h4 class="sffw-stat-card__value"><?php echo esc_html($value); ?></h4>
        <p class="sffw-stat-card__subtitle"><?php echo esc_html($subtitle); ?></p>
    </div>

</div>
</<?php echo esc_html($tag); ?>>
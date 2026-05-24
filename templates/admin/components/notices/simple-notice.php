<?php
/**
 * Component: Simple notice.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var string $id
 * @var string $type
 * @var string $message
 * @var string|null $title
 * @var string|null $icon
 * @var bool $dismissible
 * @var array $extraClasses
 */

if (!defined('ABSPATH')) {
    exit;
}

$classes = [
    'sffw-notice',
    "sffw-notice-{$type}",
];

if (!empty($dismissible)) {
    $classes[] = 'sffw-notice-dismissible';
}

if (!empty($extraClasses)) {
    $classes = array_merge($classes, $extraClasses);
}

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>">

    <?php if (!empty($icon)): ?>
        <span class="sffw-notice-icon <?php echo esc_attr($icon); ?>"></span>
    <?php endif; ?>

    <div class="sffw-notice-content">
        <?php if (!empty($title)): ?>
            <div class="sffw-notice-title">
                <?php echo esc_html($title); ?>
            </div>
        <?php endif; ?>

        <div class="sffw-notice-message">
            <?php echo wp_kses_post($message); ?>
        </div>
    </div>

    <?php if (!empty($dismissible)): ?>
        <button
            type="button"
            class="sffw-notice-close"
            aria-label="<?php esc_attr_e('Close', 'stock-forecast-for-woocommerce'); ?>"
        >
            <span class="sffw-icon--close"></span>
        </button>
    <?php endif; ?>
</div>
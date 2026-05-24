<?php
/**
 * Component: Pagination.
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 * phpcs:disable WordPress.Security.NonceVerification
 *
 * @package StockForecastForWooCommerce
 * @version 1.0.0
 *
 * @var int $current
 * @var int $perPage
 * @var int $total
 * @var int $pages
 */

if (!defined('ABSPATH')) {
    exit;
}

$current = max(1, $current);
$pages   = max(1, $pages);
$total   = max(0, $total);

$baseUrl   = remove_query_arg('paged');
$queryArgs = $_GET;
unset($queryArgs['paged']);

$baseWithParams = add_query_arg($queryArgs, $baseUrl);

$isFirstDisabled = ($current <= 1);
$isLastDisabled  = ($current >= $pages);

$firstPage = 1;
$prevPage  = max(1, $current - 1);
$nextPage  = min($pages, $current + 1);
$lastPage  = $pages;

$firstUrl = add_query_arg('paged', $firstPage, $baseWithParams);
$prevUrl  = add_query_arg('paged', $prevPage, $baseWithParams);
$nextUrl  = add_query_arg('paged', $nextPage, $baseWithParams);
$lastUrl  = add_query_arg('paged', $lastPage, $baseWithParams);
?>
<div class="sffw-pagination" data-sffw-pagination="true">

    <span class="sffw-displaying-num">
        <?php echo esc_html(number_format_i18n($total)); ?>
        <?php esc_html_e('items', 'stock-forecast-for-woocommerce'); ?>
    </span>

    <?php if ($isFirstDisabled): ?>
        <span class="sffw-page-link sffw-page-first sffw-disabled">«</span>
    <?php else: ?>
        <a class="sffw-page-link sffw-page-first"
           href="<?php echo esc_url($firstUrl); ?>"
           data-sffw-page="<?php echo esc_attr($firstPage); ?>">
            «
        </a>
    <?php endif; ?>

    <?php if ($isFirstDisabled): ?>
        <span class="sffw-page-link sffw-page-prev sffw-disabled">‹</span>
    <?php else: ?>
        <a class="sffw-page-link sffw-page-prev"
           href="<?php echo esc_url($prevUrl); ?>"
           data-sffw-page="<?php echo esc_attr($prevPage); ?>">
            ‹
        </a>
    <?php endif; ?>

    <div class="sffw-page-input">
        <input
            type="text"
            name="paged"
            class="sffw-form-control sffw-paged"
            value="<?php echo esc_attr($current); ?>"
            min="1"
            max="<?php echo esc_attr($pages); ?>"
            data-sffw-current-page="<?php echo esc_attr($current); ?>"
        >
        <span class="sffw-page-total">
            <?php esc_html_e('of', 'stock-forecast-for-woocommerce'); ?>
            <?php echo esc_html(number_format_i18n($pages)); ?>
        </span>
    </div>

    <?php if ($isLastDisabled): ?>
        <span class="sffw-page-link sffw-page-next sffw-disabled">›</span>
    <?php else: ?>
        <a class="sffw-page-link sffw-page-next"
           href="<?php echo esc_url($nextUrl); ?>"
           data-sffw-page="<?php echo esc_attr($nextPage); ?>">
            ›
        </a>
    <?php endif; ?>

    <?php if ($isLastDisabled): ?>
        <span class="sffw-page-link sffw-page-last sffw-disabled">»</span>
    <?php else: ?>
        <a class="sffw-page-link sffw-page-last"
           href="<?php echo esc_url($lastUrl); ?>"
           data-sffw-page="<?php echo esc_attr($lastPage); ?>">
            »
        </a>
    <?php endif; ?>

</div>
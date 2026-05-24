<?php

namespace StockForecastForWooCommerce\Utils;

use StockForecastForWooCommerce\Config\PrefixConfig;
use WC_Product;
use WC_Product_Variation;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DisplayUtils.
 *
 * Utility class for rendering display strings used in the plugin.
 *
 * @package StockForecastForWooCommerce\Utils
 * @version 1.0.0
 */
class DisplayUtils
{
    /**
     * Returns a localized string showing how long ago the forecast was last updated.
     * If the timestamp is missing or invalid, returns a placeholder text.
     *
     * @param int|null $forecastLastUpdated
     * @return string
     */
    public static function getForecastLastUpdatedDisplay(?int $forecastLastUpdated = null): string
    {
        if ($forecastLastUpdated === null || $forecastLastUpdated <= 0) {
            return __('Updating forecasts...', 'stock-forecast-for-woocommerce');
        }

        $timeDiff = human_time_diff($forecastLastUpdated, DateTimeUtils::timestamp());

        // translators: %s: Time difference since the last update.
        return sprintf(__('Last updated %s', 'stock-forecast-for-woocommerce'), $timeDiff);
    }

    /**
     * Convert a risk level key into a UI‑ready label + CSS class.
     *
     * @param string $risk
     * @return array{label: string, class: string}
     */
    public static function formatRiskLevel(string $risk): array
    {
        switch ($risk) {
            case 'critical':
                return [
                    'label' => __('Critical', 'stock-forecast-for-woocommerce'),
                    'class' => PrefixConfig::css('badge') . ' ' . PrefixConfig::css('badge--error'),
                ];

            case 'warning':
                return [
                    'label' => __('Warning', 'stock-forecast-for-woocommerce'),
                    'class' => PrefixConfig::css('badge') . ' ' . PrefixConfig::css('badge--warning'),
                ];

            case 'safe':
                return [
                    'label' => __('Safe', 'stock-forecast-for-woocommerce'),
                    'class' => PrefixConfig::css('badge') . ' ' . PrefixConfig::css('badge--success'),
                ];

            case 'out_of_stock':
                return [
                    'label' => __('Out of Stock', 'stock-forecast-for-woocommerce'),
                    'class' => PrefixConfig::css('badge') . ' ' . PrefixConfig::css('badge--error'),
                ];

            case 'backordering':
                return [
                    'label' => __('Backordering', 'stock-forecast-for-woocommerce'),
                    'class' => PrefixConfig::css('badge') . ' ' . PrefixConfig::css('badge--info'),
                ];

            default:
                return [
                    'label' => ucfirst($risk),
                    'class' => PrefixConfig::css('badge') . ' ' . PrefixConfig::css($risk),
                ];
        }
    }

    /**
     * Returns the formatted product display name for simple or variable products.
     *
     * Simple products → Product Name
     * Variations → Parent Product Name — Variation Attributes
     *
     * @param WC_Product $product WooCommerce product object (simple or variation).
     * @return string Human‑friendly product title for UI display.
     */
    public static function getProductDisplayName(WC_Product $product): string
    {
        if ($product instanceof WC_Product_Variation) {
            $title = $product->get_title();
            $attrs = $product->get_attribute_summary();

            if ($attrs) {
                return sprintf(
                    '%s<span class="%s %s %s %s">%s</span>',
                    $title,
                    PrefixConfig::css('d-block'),
                    PrefixConfig::css('mt-1'),
                    PrefixConfig::css('small'),
                    PrefixConfig::css('text-muted'),
                    $attrs
                );
            }

            return $title;
        }

        return $product->get_name();
    }

    /**
     * Returns a human‑friendly "time ago" string for a MySQL datetime value.
     *
     * @param string|null $datetime MySQL datetime (Y-m-d H:i:s).
     * @return string
     */
    public static function getForecastLastCalculatedDisplay(?string $datetime): string
    {
        if (empty($datetime)) {
            return __('Never', 'stock-forecast-for-woocommerce');
        }

        $timestamp = strtotime($datetime);

        if (!$timestamp) {
            return __('Never', 'stock-forecast-for-woocommerce');
        }

        $timeDiff = human_time_diff($timestamp, DateTimeUtils::timestamp());

        // translators: %s: Time difference.
        return sprintf(__('%s ago', 'stock-forecast-for-woocommerce'), $timeDiff);
    }
}
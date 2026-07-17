<?php

namespace StockForecastForWooCommerce\Utils;

use StockForecastForWooCommerce\Config\PrefixConfig;
use WC_Product;
use WC_Product_Variation;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Formats plugin display values.
 *
 * @package StockForecastForWooCommerce\Utils
 * @since   1.0.0
 */
class DisplayUtils
{
    /** Gets the forecast last updated display. */
    public static function getForecastLastUpdatedDisplay(?int $forecastLastUpdated = null): string
    {
        if ($forecastLastUpdated === null || $forecastLastUpdated <= 0) {
            return __('Updating forecasts...', 'stock-forecast-for-woocommerce');
        }

        $timeDiff = human_time_diff($forecastLastUpdated, DateTimeUtils::timestamp());

        // translators: %s: Time difference since the last update.
        return sprintf(__('Last updated %s', 'stock-forecast-for-woocommerce'), $timeDiff);
    }

    /** Formats a risk level for display. */
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

    /** Gets the product display name. */
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

    /** Gets the forecast last calculated display. */
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
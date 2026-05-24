<?php

namespace StockForecastForWooCommerce\Services\Admin\Dashboard;

use StockForecastForWooCommerce\DataProviders\ForecastDataProvider;
use StockForecastForWooCommerce\Config\PrefixConfig;
use StockForecastForWooCommerce\Utils\DisplayUtils;
use StockForecastForWooCommerce\Utils\MenuUtils;
use StockForecastForWooCommerce\Config\PluginMeta;
use StockForecastForWooCommerce\Utils\OptionUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DashboardData
 *
 * @package StockForecastForWooCommerce\Services\Admin\Dashboard
 * @version 1.0.0
 */
class DashboardData
{
    /**
     * @var ForecastDataProvider
     */
    private ForecastDataProvider $forecastDataProvider;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->forecastDataProvider = new ForecastDataProvider();
    }

    /**
     * Returns an array of statistics related to product stock forecasts.
     *
     * @return array
     */
    public function getStats(): array
    {
        $forecastStats = $this->forecastDataProvider->getForecastStats();

        return [
            'total'        => [
                'icon'     => PrefixConfig::css('icon--inventory'),
                'label'    => __('Products Analyzed', 'stock-forecast-for-woocommerce'),
                'subtitle' => __('Included in forecast model', 'stock-forecast-for-woocommerce'),
                'value'    => (int)$forecastStats['total'],
                'color'    => 'primary',
                'link'     => MenuUtils::getUrl('product-forecast'),
            ],
            'safe'         => [
                'icon'     => PrefixConfig::css('icon--check-circle'),
                'label'    => __('Safe Stock', 'stock-forecast-for-woocommerce'),
                'subtitle' => __('No restock needed', 'stock-forecast-for-woocommerce'),
                'value'    => (int)$forecastStats['safe'],
                'color'    => 'success',
                'link'     => MenuUtils::getUrl('product-forecast', ['risk_level' => 'safe']),
            ],
            'warning'      => [
                'icon'     => PrefixConfig::css('icon--alert'),
                'label'    => __('Low Stock', 'stock-forecast-for-woocommerce'),
                'subtitle' => __('At risk of shortage', 'stock-forecast-for-woocommerce'),
                'value'    => (int)$forecastStats['warning'],
                'color'    => 'warning',
                'link'     => MenuUtils::getUrl('product-forecast', ['risk_level' => 'warning']),
            ],
            'critical'     => [
                'icon'     => PrefixConfig::css('icon--critical'),
                'label'    => __('Critical Stock', 'stock-forecast-for-woocommerce'),
                'subtitle' => __('Restock immediately', 'stock-forecast-for-woocommerce'),
                'value'    => (int)$forecastStats['critical'],
                'color'    => 'error',
                'link'     => MenuUtils::getUrl('product-forecast', ['risk_level' => 'critical']),
            ],
            'out_of_stock' => [
                'icon'     => PrefixConfig::css('icon--inventory-empty'),
                'label'    => __('Out of Stock', 'stock-forecast-for-woocommerce'),
                'subtitle' => __('Unavailable for sale', 'stock-forecast-for-woocommerce'),
                'value'    => (int)$forecastStats['out_of_stock'],
                'color'    => 'error',
                'link'     => MenuUtils::getUrl('product-forecast', ['risk_level' => 'out_of_stock']),
            ],
            'backordering' => [
                'icon'     => PrefixConfig::css('icon--backorder'),
                'label'    => __('Backorders', 'stock-forecast-for-woocommerce'),
                'subtitle' => __('Sold without stock', 'stock-forecast-for-woocommerce'),
                'value'    => (int)$forecastStats['backordering'],
                'color'    => 'info',
                'link'     => MenuUtils::getUrl('product-forecast', ['risk_level' => 'backordering']),
            ],
        ];
    }

    /**
     * Returns a formatted string showing when the forecast was last updated.
     *
     * @return string
     */
    public function getForecastLastUpdatedDisplay(): string
    {
        $forecastLastUpdated = (int)OptionUtils::getMeta(PluginMeta::FORECAST_LAST_UPDATED);

        return DisplayUtils::getForecastLastUpdatedDisplay($forecastLastUpdated);
    }

    /**
     * Prepare critical products data for the dashboard table.
     *
     * Fetches products at highest risk of stockout, preloads WooCommerce products,
     * and formats the data structure for UI display.
     *
     * @return array<int, array<string, mixed>> List of formatted product rows for the dashboard.
     */
    public function getCriticalProductsData(): array
    {
        $forecasts = $this->forecastDataProvider->getCriticalProducts();

        if (!$forecasts) {
            return [
                'hasData' => false,
                'title'   => __('No Products in Critical Stock', 'stock-forecast-for-woocommerce'),
                'text'    => __('None of your products are currently predicted to reach critical stock levels.', 'stock-forecast-for-woocommerce'),
                'icon'    => PrefixConfig::css('icon--check-circle'),
                'color'   => 'success',
            ];
        }

        // collect product ids
        $productIds = array_map(static function ($forecast) {
            return (int)($forecast->variation_id ?: $forecast->product_id);
        }, $forecasts);

        // preload products
        $products = wc_get_products(
            [
                'include' => $productIds,
                'limit'   => -1,
                'type'    => [
                    'simple',
                    'variation',
                ],
            ]
        );

        // map products by id
        $productMap = [];
        foreach ($products as $product) {
            $productMap[$product->get_id()] = $product;
        }

        $data = [];

        foreach ($forecasts as $forecast) {

            $lookupId = $forecast->variation_id ?: $forecast->product_id;

            $product = $productMap[$lookupId] ?? null;

            if (!$product) {
                continue;
            }

            $productLink = $product->is_type('variation')
                ? get_edit_post_link($product->get_parent_id())
                : get_edit_post_link($product->get_id());

            $data[] = [
                'product'             => [
                    'label' => DisplayUtils::getProductDisplayName($product),
                    'link'  => $productLink,
                ],
                'current_stock'       => $forecast->current_stock,
                'daily_sales'         => $forecast->daily_sales,
                'days_until_stockout' => $forecast->days_until_stockout,
                'risk_level'          => DisplayUtils::formatRiskLevel($forecast->risk_level),
            ];
        }

        return [
            'hasData' => true,
            'rows'    => $data,
            'columns' => [
                [
                    'key'   => 'product',
                    'label' => __('Product', 'stock-forecast-for-woocommerce'),
                    'type'  => 'link',
                    'class' => PrefixConfig::css('col-product') . ' ' . PrefixConfig::css('text-nowrap'),
                ],
                [
                    'key'   => 'current_stock',
                    'label' => __('Available Stock', 'stock-forecast-for-woocommerce'),
                    'type'  => 'number',
                    'class' => PrefixConfig::css('col-stock'),
                ],
                [
                    'key'      => 'daily_sales',
                    'label'    => __('Daily Sales', 'stock-forecast-for-woocommerce'),
                    'type'     => 'decimal',
                    'decimals' => 2,
                    'suffix'   => __(' / day', 'stock-forecast-for-woocommerce'),
                    'class'    => PrefixConfig::css('col-daily-sales'),
                ],
                [
                    'key'   => 'days_until_stockout',
                    'label' => __('Estimated Days Remaining', 'stock-forecast-for-woocommerce'),
                    'type'  => 'days',
                    'class' => PrefixConfig::css('col-days-until-stockout'),
                ],
                [
                    'key'   => 'risk_level',
                    'label' => __('Stockout Risk', 'stock-forecast-for-woocommerce'),
                    'type'  => 'badge',
                    'class' => PrefixConfig::css('col-risk-level'),
                ]
            ],
            'class'   => PrefixConfig::css('table--striped'),
        ];
    }
}
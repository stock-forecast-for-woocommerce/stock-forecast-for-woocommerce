<?php

namespace StockForecastForWooCommerce\Services\Admin\ProductForecast;

use StockForecastForWooCommerce\Config\PluginMeta;
use StockForecastForWooCommerce\Config\PrefixConfig;
use StockForecastForWooCommerce\Utils\DisplayUtils;
use StockForecastForWooCommerce\Utils\OptionUtils;
use StockForecastForWooCommerce\DataProviders\ForecastDataProvider;
use StockForecastForWooCommerce\Utils\Request;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Provides product forecast data for the admin interface.
 *
 * @package StockForecastForWooCommerce\Services\Admin\ProductForecast
 * @since   1.0.0
 */
class ProductForecastData
{
    /** Forecast data provider. */
    private ForecastDataProvider $forecastDataProvider;

    /** Initializes the product forecast data service. */
    public function __construct()
    {
        $this->forecastDataProvider = new ForecastDataProvider();
    }

    /** Gets the forecast last updated display string. */
    public function getForecastLastUpdatedDisplay(): string
    {
        $forecastLastUpdated = (int)OptionUtils::getMeta(PluginMeta::FORECAST_LAST_UPDATED);

        return DisplayUtils::getForecastLastUpdatedDisplay($forecastLastUpdated);
    }

    /** Gets forecast data. */
    public function getForecastsData(): array
    {
        $page    = Request::int('paged', 1);
        $perPage = Request::int('per_page', 20);
        $orderBy = Request::key('orderby', 'id');
        $order   = Request::key('order', 'DESC');

        $filters = [
            'risk_level'          => Request::str('risk_level'),
            'product_type'        => Request::str('product_type'),
            'search'              => Request::str('search'),
            'days_until_stockout' => Request::int('days_until_stockout'),
            'current_stock'       => Request::str('current_stock'),
            'daily_sales'         => Request::str('daily_sales'),
        ];

        $forecasts = $this->forecastDataProvider->getForecasts(
            $perPage,
            $page,
            $orderBy,
            $order,
            $filters
        );

        if (empty($forecasts['items'])) {
            $hasFilters = !empty(array_filter($filters));

            return [
                'hasData'    => false,
                'title'      => $hasFilters
                    ? __('No results found', 'stock-forecast-for-woocommerce')
                    : __('No Products Available', 'stock-forecast-for-woocommerce'),
                'text'       => $hasFilters
                    ? __('Try adjusting your search or filters to find what you are looking for.', 'stock-forecast-for-woocommerce')
                    : __('There are no products available to generate stock forecasts yet.', 'stock-forecast-for-woocommerce'),
                'icon'       => $hasFilters ? PrefixConfig::css('icon--search') : PrefixConfig::css('icon--inventory'),
                'color'      => 'info',
                'pagination' => [
                    'current' => $page,
                    'perPage' => $perPage,
                    'total'   => $forecasts['total'],
                    'pages'   => $forecasts['pages'],
                ],
                'filters'    => $filters,
            ];
        }

        $productIds = array_map(static function ($forecast) {
            return (int)($forecast->variation_id ?: $forecast->product_id);
        }, $forecasts['items']);

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

        $productMap = [];
        foreach ($products as $product) {
            $productMap[$product->get_id()] = $product;
        }

        $data = [];

        foreach ($forecasts['items'] as $forecast) {
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
                'sku'                 => $forecast->sku ?: __('N/A', 'stock-forecast-for-woocommerce'),
                'current_stock'       => $forecast->current_stock,
                'daily_sales'         => $forecast->daily_sales,
                'days_until_stockout' => $forecast->days_until_stockout,
                'risk_level'          => DisplayUtils::formatRiskLevel($forecast->risk_level),
                'last_calculated'     => DisplayUtils::getForecastLastCalculatedDisplay($forecast->last_calculated),
            ];
        }

        return [
            'hasData'    => true,
            'rows'       => $data,
            'columns'    => [
                [
                    'key'   => 'product',
                    'label' => __('Product', 'stock-forecast-for-woocommerce'),
                    'type'  => 'link',
                    'class' => PrefixConfig::css('col-product') . ' ' . PrefixConfig::css('text-nowrap'),
                ],
                [
                    'key'   => 'sku',
                    'label' => __('SKU', 'stock-forecast-for-woocommerce'),
                    'type'  => 'text',
                    'class' => PrefixConfig::css('col-sku') . ' ' . PrefixConfig::css('d-none') . ' ' . PrefixConfig::css('d-sm-table-cell'),
                ],
                [
                    'key'    => 'current_stock',
                    'label'  => __('Available Stock', 'stock-forecast-for-woocommerce'),
                    'type'   => 'number',
                    'class'  => PrefixConfig::css('col-stock'),
                    'labels' => [
                        'negative' => __('Backorder', 'stock-forecast-for-woocommerce')
                    ]
                ],
                [
                    'key'      => 'daily_sales',
                    'label'    => __('Daily Sales', 'stock-forecast-for-woocommerce'),
                    'type'     => 'decimal',
                    'decimals' => 2,
                    'suffix'   => __(' / day', 'stock-forecast-for-woocommerce'),
                    'class'    => PrefixConfig::css('col-daily-sales') . ' ' . PrefixConfig::css('d-none') . ' ' . PrefixConfig::css('d-sm-table-cell'),
                    'labels'   => [
                        'zero' => __('No sales', 'stock-forecast-for-woocommerce'),
                    ],
                ],
                [
                    'key'    => 'days_until_stockout',
                    'label'  => __('Estimated Days Remaining', 'stock-forecast-for-woocommerce'),
                    'type'   => 'days',
                    'class'  => PrefixConfig::css('col-days-until-stockout'),
                    'labels' => [
                        'empty' => __('Out of stock', 'stock-forecast-for-woocommerce'),
                        'unit'  => __('days', 'stock-forecast-for-woocommerce'),
                    ],
                ],
                [
                    'key'   => 'risk_level',
                    'label' => __('Stockout Risk', 'stock-forecast-for-woocommerce'),
                    'type'  => 'badge',
                    'class' => PrefixConfig::css('col-risk-level'),
                ],
                [
                    'key'   => 'last_calculated',
                    'label' => __('Last Calculated', 'stock-forecast-for-woocommerce'),
                    'type'  => 'datetime',
                    'class' => PrefixConfig::css('col-last-calculated') . ' ' . PrefixConfig::css('d-none') . ' ' . PrefixConfig::css('d-sm-table-cell'),
                ]
            ],
            'class'      => PrefixConfig::css('table--striped'),
            'pagination' => [
                'current' => $page,
                'perPage' => $perPage,
                'total'   => $forecasts['total'],
                'pages'   => $forecasts['pages'],
            ],
            'filters'    => $filters,
        ];
    }
}
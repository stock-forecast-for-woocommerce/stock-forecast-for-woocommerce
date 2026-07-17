<?php

namespace StockForecastForWooCommerce\Services\Admin\ProductForecast;

use StockForecastForWooCommerce\Abstracts\AbstractAdminPage;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Displays the Product Forecast admin page.
 *
 * @package StockForecastForWooCommerce\Services\Admin\ProductForecast
 * @since   1.0.0
 */
class ProductForecastPage extends AbstractAdminPage
{
    /** Product forecast data service. */
    protected ProductForecastData $productForecastData;

    /** Initializes the product forecast page. */
    protected function __construct()
    {
        parent::__construct();

        $this->productForecastData = new ProductForecastData();
    }

    /** Gets the page header template. */
    protected function getPageHeaderTemplate(): string
    {
        return 'admin/pages/product-forecast/header';
    }

    /** Gets the page template. */
    protected function getTemplate(): string
    {
        return 'admin/pages/product-forecast/content';
    }

    /** Gets the page header context. */
    protected function getPageHeaderContext(): array
    {
        return [
            'forecastLastUpdatedDisplay' => $this->productForecastData->getForecastLastUpdatedDisplay(),
        ];
    }

    /** Gets the page body context. */
    protected function getBodyContext(): array
    {
        return [
            'forecastsData' => $this->productForecastData->getForecastsData(),
        ];
    }
}
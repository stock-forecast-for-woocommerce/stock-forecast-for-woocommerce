<?php

namespace StockForecastForWooCommerce\Services\Admin\ProductForecast;

use StockForecastForWooCommerce\Abstracts\AbstractAdminPage;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ProductForecastPage
 *
 * Admin page displaying product stock forecasts.
 *
 * @package StockForecastForWooCommerce\Services\Admin\ProductForecast
 * @version 1.0.0
 */
class ProductForecastPage extends AbstractAdminPage
{
    /**
     * Holds ProductForecastData instance for data access.
     *
     * @var ProductForecastData
     */
    protected ProductForecastData $productForecastData;

    /**
     * ProductForecastPage constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        $this->productForecastData = new ProductForecastData();
    }

    /**
     * Get page header template path.
     *
     * @return string
     */
    protected function getPageHeaderTemplate(): string
    {
        return 'admin/pages/product-forecast/header';
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    protected function getTemplate(): string
    {
        return 'admin/pages/product-forecast/content';
    }

    /**
     * Get page header template context.
     *
     * @return array
     */
    protected function getPageHeaderContext(): array
    {
        return [
            'forecastLastUpdatedDisplay' => $this->productForecastData->getForecastLastUpdatedDisplay(),
        ];
    }

    /**
     * Provide template data.
     *
     * @return array
     */
    protected function getBodyContext(): array
    {
        return [
            'forecastsData' => $this->productForecastData->getForecastsData(),
        ];
    }
}
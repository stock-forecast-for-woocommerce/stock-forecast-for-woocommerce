<?php

namespace StockForecastForWooCommerce\Services\Admin\Dashboard;

use StockForecastForWooCommerce\Abstracts\AbstractAdminPage;
use StockForecastForWooCommerce\Utils\MenuUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Displays the plugin dashboard page.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Dashboard
 * @since   1.0.0
 */
class DashboardPage extends AbstractAdminPage
{
    /** Dashboard data provider. */
    protected DashboardData $dashboardData;

    /** Initialize the dashboard page. */
    protected function __construct()
    {
        parent::__construct();

        $this->dashboardData = new DashboardData();
    }

    /** Get the page header template. */
    protected function getPageHeaderTemplate(): string
    {
        return 'admin/pages/dashboard/header';
    }

    /** Get the page body template. */
    protected function getTemplate(): string
    {
        return 'admin/pages/dashboard/content';
    }

    /** Get the page header context. */
    protected function getPageHeaderContext(): array
    {
        return [
            'forecastLastUpdatedDisplay' => $this->dashboardData->getForecastLastUpdatedDisplay(),
            'forecastUpdateLink'         => MenuUtils::getUrl('product-forecast'),
        ];
    }

    /** Get the page body context. */
    protected function getBodyContext(): array
    {
        return [
            'stats'                => $this->dashboardData->getStats(),
            'criticalProductsData' => $this->dashboardData->getCriticalProductsData(),
        ];
    }
}
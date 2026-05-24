<?php

namespace StockForecastForWooCommerce\Services\Admin\Dashboard;

use StockForecastForWooCommerce\Abstracts\AbstractAdminPage;
use StockForecastForWooCommerce\Utils\MenuUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DashboardPage
 *
 * Admin dashboard page for the plugin.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Dashboard
 * @version 1.0.0
 */
class DashboardPage extends AbstractAdminPage
{
    /**
     * Holds DashboardData instance for data access.
     *
     * @var DashboardData
     */
    protected DashboardData $dashboardData;

    /**
     * ProductForecastPage constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        $this->dashboardData = new DashboardData();
    }

    /**
     * Get page header template path.
     *
     * @return string
     */
    protected function getPageHeaderTemplate(): string
    {
        return 'admin/pages/dashboard/header';
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    protected function getTemplate(): string
    {
        return 'admin/pages/dashboard/content';
    }

    /**
     * Get page header template context.
     *
     * @return array
     */
    protected function getPageHeaderContext(): array
    {
        return [
            'forecastLastUpdatedDisplay' => $this->dashboardData->getForecastLastUpdatedDisplay(),
            'forecastUpdateLink'         => MenuUtils::getUrl('product-forecast'),
        ];
    }

    /**
     * Provide data for the template.
     *
     * @return array
     */
    protected function getBodyContext(): array
    {
        return [
            'stats'                => $this->dashboardData->getStats(),
            'criticalProductsData' => $this->dashboardData->getCriticalProductsData(),
        ];
    }
}
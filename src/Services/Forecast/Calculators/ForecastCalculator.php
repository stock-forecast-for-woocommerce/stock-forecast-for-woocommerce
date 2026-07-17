<?php

namespace StockForecastForWooCommerce\Services\Forecast\Calculators;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculates stock depletion forecasts.
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Calculators
 * @since   1.0.0
 */
class ForecastCalculator
{
    /** Calculate daily sales and projected stockout time. */
    public function calculate(int $currentStock, float $unitsSold, int $salesWindow): array
    {
        if ($salesWindow <= 0) {
            return [
                'daily_sales'         => 0.0,
                'days_until_stockout' => null,
            ];
        }

        $dailySales = $unitsSold / $salesWindow;

        if ($dailySales <= 0) {
            return [
                'daily_sales'         => 0.0,
                'days_until_stockout' => null,
            ];
        }

        if ($currentStock <= 0) {
            return [
                'daily_sales'         => $dailySales,
                'days_until_stockout' => 0.0,
            ];
        }

        $daysUntilStockout = $currentStock / $dailySales;

        return [
            'daily_sales'         => $dailySales,
            'days_until_stockout' => $daysUntilStockout,
        ];
    }
}
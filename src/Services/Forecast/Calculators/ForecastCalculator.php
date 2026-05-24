<?php

namespace StockForecastForWooCommerce\Services\Forecast\Calculators;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ForecastCalculator
 *
 * Handles the mathematical derivation of daily sales velocity and stockout projections.
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Calculators
 * @version 1.0.0
 */
class ForecastCalculator
{
    /**
     * Calculates the daily sales velocity and projected days until stockout.
     *
     * @param int $currentStock The current physical stock level.
     * @param float $unitsSold Total units sold within the defined period.
     * @param int $salesWindow The number of days in the sales period (e.g., 30).
     *
     * @return array {
     * @var float $dailySales Units sold per day.
     * @var float|null $daysUntilStockout Days remaining until stock reaches zero.
     * }
     */
    public function calculate(int $currentStock, float $unitsSold, int $salesWindow): array
    {
        // Guard against division by zero if an empty window is provided
        if ($salesWindow <= 0) {
            return [
                'daily_sales'         => 0.0,
                'days_until_stockout' => null
            ];
        }

        // Formula: Daily Sales = Units Sold / Sales Window
        $dailySales = $unitsSold / $salesWindow;

        // Condition: Zero Sales
        if ($dailySales <= 0) {
            return [
                'daily_sales'         => 0.0,
                'days_until_stockout' => null
            ];
        }

        // Condition: Negative or Zero Stock
        if ($currentStock <= 0) {
            return [
                'daily_sales'         => $dailySales,
                'days_until_stockout' => 0.0
            ];
        }

        // Formula: Days Until Stockout = Current Stock / Daily Sales
        $daysUntilStockout = $currentStock / $dailySales;

        return [
            'daily_sales'         => $dailySales,
            'days_until_stockout' => $daysUntilStockout
        ];
    }
}
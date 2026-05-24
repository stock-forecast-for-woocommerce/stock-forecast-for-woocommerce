<?php

namespace StockForecastForWooCommerce\Services\Forecast\Calculators;

use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\Utils\OptionUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class RiskEvaluator
 *
 * Assigns a risk status based on stock levels and projected stockout timelines.
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Calculators
 * @version 1.0.0
 */
class RiskEvaluator
{
    /**
     * Evaluates the stock risk level based on configured thresholds.
     *
     * @param int $currentStock The current physical stock level.
     * @param float|null $daysUntilStockout Calculated days remaining, or null if no sales.
     * @param string $backorderStatus Value of '_backorders' meta (no, yes, notify).
     *
     * @return string The risk level: 'out_of_stock', 'critical', 'warning', or 'safe'.
     */
    public function evaluate(int $currentStock, ?float $daysUntilStockout, string $backorderStatus = 'no'): string
    {
        // Rule: Negative or Zero Stock
        if ($currentStock <= 0) {
            return ($backorderStatus === 'no') ? 'out_of_stock' : 'backordering';
        }

        // Rule: Zero Sales (represented by null days_until_stockout)
        if ($daysUntilStockout === null) {
            return 'safe';
        }

        // Fetch thresholds from system options
        $criticalThreshold = (int)OptionUtils::getOption(
            OptionUtils::makeKey(
                PluginSettings::SECTION_FORECAST,
                PluginSettings::CRITICAL_DAYS
            ),
            7
        );
        $warningThreshold  = (int)OptionUtils::getOption(
            OptionUtils::makeKey(
                PluginSettings::SECTION_FORECAST,
                PluginSettings::WARNING_DAYS
            ),
            14
        );

        // Rule: Critical
        if ($daysUntilStockout <= $criticalThreshold) {
            return 'critical';
        }

        // Rule: Warning
        if ($daysUntilStockout <= $warningThreshold) {
            return 'warning';
        }

        // Rule: Safe (Default)
        return 'safe';
    }
}
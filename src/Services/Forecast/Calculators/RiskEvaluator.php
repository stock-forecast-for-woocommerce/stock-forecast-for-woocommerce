<?php

namespace StockForecastForWooCommerce\Services\Forecast\Calculators;

use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\Utils\OptionUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Evaluates stock risk levels from forecast data.
 *
 * @package StockForecastForWooCommerce\Services\Forecast\Calculators
 * @since   1.0.0
 */
class RiskEvaluator
{
    /** Evaluate the stock risk level. */
    public function evaluate(int $currentStock, ?float $daysUntilStockout, string $backorderStatus = 'no'): string
    {
        if ($currentStock <= 0) {
            return ($backorderStatus === 'no') ? 'out_of_stock' : 'backordering';
        }

        if ($daysUntilStockout === null) {
            return 'safe';
        }

        $criticalThreshold = (int)OptionUtils::getOption(
            OptionUtils::makeKey(
                PluginSettings::SECTION_FORECAST,
                PluginSettings::CRITICAL_DAYS
            ),
            7
        );

        $warningThreshold = (int)OptionUtils::getOption(
            OptionUtils::makeKey(
                PluginSettings::SECTION_FORECAST,
                PluginSettings::WARNING_DAYS
            ),
            14
        );

        if ($daysUntilStockout <= $criticalThreshold) {
            return 'critical';
        }

        if ($daysUntilStockout <= $warningThreshold) {
            return 'warning';
        }

        return 'safe';
    }
}
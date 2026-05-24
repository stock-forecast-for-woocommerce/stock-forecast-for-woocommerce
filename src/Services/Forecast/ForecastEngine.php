<?php

namespace StockForecastForWooCommerce\Services\Forecast;

use StockForecastForWooCommerce\Services\Forecast\Calculators\ForecastCalculator;
use StockForecastForWooCommerce\Services\Forecast\Calculators\RiskEvaluator;
use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\DataProviders\ProductDataProvider;
use StockForecastForWooCommerce\DataProviders\SalesDataProvider;
use StockForecastForWooCommerce\Models\Forecast;
use StockForecastForWooCommerce\Utils\OptionUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ForecastEngine
 *
 * Handles stock forecast calculations for WooCommerce products.
 *
 * @package StockForecastForWooCommerce\Services\Forecast
 * @version 1.0.0
 */
class ForecastEngine
{
    /**
     * Process forecast for a single product.
     *
     * @param int $id
     * @return void
     */
    public function processProduct(int $id): void
    {
        if ($id > 0) {
            $this->processProducts([$id]);
        }
    }

    /**
     * Process forecast for multiple products.
     *
     * @param array $ids
     * @return void
     */
    public function processProducts(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $productProvider = new ProductDataProvider();
        $salesProvider   = new SalesDataProvider();

        $calculator = new ForecastCalculator();
        $evaluator  = new RiskEvaluator();

        $entities = $productProvider->expandProducts($ids);
        if (empty($entities)) {
            return;
        }

        $metaMap = $productProvider->fetchMetadata($entities);

        $window   = (int)OptionUtils::getOption(
            OptionUtils::makeKey(
                PluginSettings::SECTION_FORECAST,
                PluginSettings::SALES_WINDOW_DAYS
            ),
            30
        );
        $salesMap = $salesProvider->getAggregatedSales($entities, $window);

        $results  = [];
        $toDelete = [];

        foreach ($entities as $entity) {
            $pId      = $entity['product_id'];
            $vId      = $entity['variation_id'];
            $targetId = $vId ?: $pId;

            if (!isset($metaMap[$targetId])) {
                $toDelete[] = [
                    'product_id'   => $pId,
                    'variation_id' => $vId
                ];

                continue;
            }

            $meta            = $metaMap[$targetId];
            $stock           = (int)($meta['_stock'] ?? 0);
            $backorderStatus = $meta['_backorders'] ?? 'no';
            $sold            = $salesMap[$pId . '_' . $vId] ?? 0.0;

            $calc = $calculator->calculate($stock, $sold, $window);
            $risk = $evaluator->evaluate($stock, $calc['days_until_stockout'], $backorderStatus);

            $results[] = [
                'product_id'          => $pId,
                'variation_id'        => $vId,
                'sku'                 => $meta['_sku'],
                'product_type'        => $entity['type'],
                'current_stock'       => $stock,
                'daily_sales'         => $calc['daily_sales'],
                'days_until_stockout' => $calc['days_until_stockout'],
                'risk_level'          => $risk
            ];
        }

        if (!empty($results)) {
            Forecast::upsertMany($results);
        }

        if (!empty($toDelete)) {
            Forecast::deleteMany($toDelete);
        }
    }
}
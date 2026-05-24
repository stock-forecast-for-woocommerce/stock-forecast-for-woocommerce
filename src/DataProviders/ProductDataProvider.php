<?php

namespace StockForecastForWooCommerce\DataProviders;

use StockForecastForWooCommerce\Abstracts\AbstractDataProvider;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ProductDataProvider
 *
 * Handles Product Expansion and Metadata Retrieval.
 *
 * @package StockForecastForWooCommerce\DataProviders
 * @version 1.0.0
 */
class ProductDataProvider extends AbstractDataProvider
{
    /**
     * Expands a list of IDs into Forecastable Entities (Simple + Variations).
     *
     * @param array $ids
     * @return array
     */
    public function expandProducts(array $ids): array
    {
        global $wpdb;

        if (empty($ids)) {
            return [];
        }

        $ids          = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT 
                    p.ID AS variation_id,
                    p.post_parent AS product_id
                 FROM {$wpdb->posts} p
                 WHERE p.post_type = 'product_variation'
                 AND p.post_status = 'publish'
                 AND p.post_parent IN ($placeholders)", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
                ...$ids
            ),
            ARRAY_A
        );

        $expanded        = [];
        $variableParents = [];

        foreach ($rows as $row) {
            $productId   = (int)$row['product_id'];
            $variationId = (int)$row['variation_id'];

            $expanded[] = [
                'product_id'   => $productId,
                'variation_id' => $variationId,
                'type'         => 'variation',
            ];

            $variableParents[] = $productId;
        }

        $variableParents = array_unique($variableParents);
        $simpleIds       = array_diff($ids, $variableParents);

        foreach ($simpleIds as $id) {
            $expanded[] = [
                'product_id'   => (int)$id,
                'variation_id' => 0,
                'type'         => 'simple',
            ];
        }

        return array_values($expanded);
    }

    /**
     * Fetches Metadata (SKU, Stock) for all expanded entities.
     *
     * @param array $entities
     * @return array
     */
    public function fetchMetadata(array $entities): array
    {
        global $wpdb;

        $ids = [];

        foreach ($entities as $entity) {
            $ids[] = $entity['variation_id'] ?: $entity['product_id'];
        }

        $ids = array_unique(array_filter(array_map('intval', $ids)));

        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '%d'));

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT 
                    l.product_id,
                    l.sku,
                    l.stock_quantity,
                    pm_backorder.meta_value AS backorder_status
                FROM {$wpdb->prefix}wc_product_meta_lookup l
                INNER JOIN {$wpdb->postmeta} pm_manage 
                    ON pm_manage.post_id = l.product_id 
                    AND pm_manage.meta_key = '_manage_stock' 
                    AND pm_manage.meta_value = 'yes'
                LEFT JOIN {$wpdb->postmeta} pm_backorder 
                    ON pm_backorder.post_id = l.product_id 
                    AND pm_backorder.meta_key = '_backorders'
                WHERE l.product_id IN ($placeholders)", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
                ...$ids
            ),
            ARRAY_A
        );

        $metaMap = [];

        foreach ($rows as $row) {
            $postId = (int)$row['product_id'];

            $metaMap[$postId] = [
                '_sku'        => $row['sku'],
                '_stock'      => $row['stock_quantity'],
                '_backorders' => $row['backorder_status'],
            ];
        }

        return $metaMap;
    }
}
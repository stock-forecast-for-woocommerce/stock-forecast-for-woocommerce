<?php

namespace StockForecastForWooCommerce\Sources;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Repository for WooCommerce product queries.
 *
 * Uses wc_product_meta_lookup for better performance.
 *
 * @package StockForecastForWooCommerce\Sources
 */
class WooCommerceSource
{
    /**
     * Get paginated product IDs.
     *
     * @param int $limit
     * @param int $afterId
     * @return int[]       Product IDs.
     */
    public function getProductIds(int $limit, int $afterId = 0): array
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $ids = $wpdb->get_col($wpdb->prepare(
            "
                SELECT l.product_id
                FROM {$wpdb->wc_product_meta_lookup} l
                INNER JOIN {$wpdb->posts} p
                    ON p.ID = l.product_id
                WHERE l.product_id > %d
                AND p.post_type = 'product'
                AND p.post_status = 'publish'
                ORDER BY l.product_id ASC
                LIMIT %d
                ",
            $afterId,
            $limit
        ));

        return array_map('intval', $ids);
    }
}

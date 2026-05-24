<?php

namespace StockForecastForWooCommerce\Cache;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cache key definitions used by the plugin.
 *
 * Returned keys do not include cache group prefixes.
 *
 * @package StockForecastForWooCommerce\Cache
 * @version 1.0.0
 */
final class CacheKeys
{
    /**
     * Prevent instantiation
     */
    private function __construct()
    {
    }

    /**
     * Cache key for persistent admin notices.
     *
     * Stores notices that should remain until manually dismissed.
     */
    private const KEY_ADMIN_NOTICES = 'admin_notices';

    /**
     * Cache key for flash admin notices.
     *
     * Stores one-time notices that display on the next request and auto-expire.
     */
    private const KEY_ADMIN_FLASH_NOTICES = 'admin_flash_notices';

    /**
     * Cache key used to flag when rewrite rules should be flushed.
     */
    private const KEY_FLUSH_REWRITE = 'flush_rewrite_rules';

    /**
     * Cache key used for daily cron lock prevention.
     */
    private const KEY_DAILY_LOCK = 'daily_lock';

    /**
     * Get admin notices cache key.
     *
     * @return string
     */
    public static function adminNotices(): string
    {
        return self::KEY_ADMIN_NOTICES;
    }

    /**
     * Get admin flash notices cache key.
     *
     * @return string
     */
    public static function adminFlashNotices(): string
    {
        return self::KEY_ADMIN_FLASH_NOTICES;
    }

    /**
     * Get rewrite rules flush flag key.
     *
     * @return string
     */
    public static function flushRewriteRules(): string
    {
        return self::KEY_FLUSH_REWRITE;
    }

    /**
     * Daily cron lock key.
     *
     * @return string
     */
    public static function dailyLock(): string
    {
        return self::KEY_DAILY_LOCK;
    }
}

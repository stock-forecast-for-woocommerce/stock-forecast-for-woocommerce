<?php

namespace StockForecastForWooCommerce\Cache;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cache key definitions used by the plugin.
 *
 * @package StockForecastForWooCommerce\Cache
 * @since   1.0.0
 */
class CacheKeys
{
    /** Prevent instantiation. */
    private function __construct()
    {
    }

    /** Cache key for persistent admin notices. */
    private const KEY_ADMIN_NOTICES = 'admin_notices';

    /** Cache key for flash admin notices. */
    private const KEY_ADMIN_FLASH_NOTICES = 'admin_flash_notices';

    /** Cache key used for daily cron lock prevention. */
    private const KEY_DAILY_LOCK = 'daily_lock';

    /** Get admin notices cache key. */
    public static function adminNotices(): string
    {
        return self::KEY_ADMIN_NOTICES;
    }

    /** Get admin flash notices cache key. */
    public static function adminFlashNotices(): string
    {
        return self::KEY_ADMIN_FLASH_NOTICES;
    }

    /** Daily cron lock key. */
    public static function dailyLock(): string
    {
        return self::KEY_DAILY_LOCK;
    }
}
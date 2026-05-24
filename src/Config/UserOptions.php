<?php

namespace StockForecastForWooCommerce\Config;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class UserOptions
 *
 * Centralizes all user-specific option keys (user_meta keys).
 *
 * @package StockForecastForWooCommerce\Config
 * @version 1.0.0
 */
final class UserOptions
{
    /**
     * Selected admin theme for the user.
     */
    public const ADMIN_THEME = 'admin_theme';

    /**
     * List of dismissed notice IDs.
     */
    public const DISMISSED_NOTICES = 'dismissed_notices';
}
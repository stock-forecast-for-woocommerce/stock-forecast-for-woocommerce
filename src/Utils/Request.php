<?php

namespace StockForecastForWooCommerce\Utils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Request
 *
 * Request Utility for WordPress Plugins
 *
 * Minimal helper for safely reading GET/POST parameters.
 * Uses WordPress sanitize functions and wp_unslash().
 *
 * phpcs:disable WordPress.Security.NonceVerification
 *
 * @package StockForecastForWooCommerce\Utils
 * @version 1.0.0
 */
class Request
{
    /**
     * Get data source array based on method.
     *
     * @param string $method 'get' or 'post'.
     * @return array
     */
    private static function source(string $method = 'get'): array
    {
        return strtolower($method) === 'post' ? $_POST : $_GET;
    }

    /**
     * Fetch raw (unslashed) value from the selected source.
     *
     * @param string $key
     * @param string $method 'get' or 'post'.
     * @return array|string|null
     */
    private static function value(string $key, string $method = 'get')
    {
        $src = self::source($method);

        if (!isset($src[$key])) {
            return null;
        }

        // Unslash safely
        return wp_unslash($src[$key]);
    }


    /**
     * Get sanitized array.
     *
     * @param string $key
     * @param array $default
     * @param string $method
     * @return array
     */
    public static function arr(string $key, array $default = [], string $method = 'get'): array
    {
        $inputType = $method === 'post' ? INPUT_POST : INPUT_GET;
        $value     = filter_input($inputType, $key, FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

        return is_array($value) ? wp_unslash($value) : $default;
    }

    /**
     * Get sanitized string value.
     *
     * @param string $key
     * @param string $default
     * @param string $method
     * @return string
     */
    public static function str(string $key, string $default = '', string $method = 'get'): string
    {
        $value = self::value($key, $method);
        return is_string($value) ? sanitize_text_field($value) : $default;
    }

    /**
     * Get sanitized WordPress key.
     *
     * @param string $key
     * @param string $default
     * @param string $method
     * @return string
     */
    public static function key(string $key, string $default = '', string $method = 'get'): string
    {
        $value = self::value($key, $method);
        return is_string($value) ? sanitize_key($value) : $default;
    }

    /**
     * Get sanitized text area content.
     *
     * @param string $key
     * @param string $default
     * @param string $method
     * @return string
     */
    public static function text(string $key, string $default = '', string $method = 'get'): string
    {
        $value = self::value($key, $method);
        return is_string($value) ? sanitize_textarea_field($value) : $default;
    }

    /**
     * Get sanitized URL.
     *
     * @param string $key
     * @param string $default
     * @param string $method
     * @return string
     */
    public static function url(string $key, string $default = '', string $method = 'get'): string
    {
        $value = self::value($key, $method);
        return is_string($value) ? esc_url_raw($value) : $default;
    }

    /**
     * Get integer value.
     *
     * @param string $key
     * @param int $default
     * @param string $method
     * @return int
     */
    public static function int(string $key, int $default = 0, string $method = 'get'): int
    {
        $value = self::value($key, $method);
        return is_numeric($value) ? (int)$value : $default;
    }

    /**
     * Get boolean value.
     *
     * @param string $key
     * @param bool $default
     * @param string $method
     * @return bool
     */
    public static function bool(string $key, bool $default = false, string $method = 'get'): bool
    {
        $value = self::value($key, $method);
        return $value !== null ? (bool)filter_var($value, FILTER_VALIDATE_BOOLEAN) : $default;
    }

    /**
     * Check if parameter exists.
     *
     * @param string $key
     * @param string $method
     * @return bool
     */
    public static function has(string $key, string $method = 'get'): bool
    {
        return isset(self::source($method)[$key]);
    }

    /**
     * Get request method (GET/POST).
     *
     * @return string
     */
    public static function method(): string
    {
        return isset($_SERVER['REQUEST_METHOD'])
            ? sanitize_key(wp_unslash($_SERVER['REQUEST_METHOD']))
            : 'get';
    }
}
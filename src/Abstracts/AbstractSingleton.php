<?php

namespace StockForecastForWooCommerce\Abstracts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base abstract class for creating singleton classes.
 *
 * @package StockForecastForWooCommerce\Abstracts
 * @since   1.0.0
 */
abstract class AbstractSingleton
{
    /** Holds singleton instances of all child classes. */
    private static array $instances = [];

    /** Protected constructor to prevent direct creation. */
    protected function __construct()
    {
    }

    /** Cloning is not allowed. */
    protected function __clone()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cloning is not allowed.', 'stock-forecast-for-woocommerce'), '1.0.0');
    }

    /** Instances of this class cannot be unserialized. */
    public function __wakeup(): void
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Instances of this class cannot be unserialized.', 'stock-forecast-for-woocommerce'), '1.0.0');
    }

    /**
     * Returns the singleton instance of the class.
     *
     * @return static
     */
    public static function instance(): self
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }
}
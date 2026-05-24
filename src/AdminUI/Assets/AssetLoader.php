<?php

namespace StockForecastForWooCommerce\AdminUI\Assets;

use StockForecastForWooCommerce\Abstracts\AbstractSingleton;
use StockForecastForWooCommerce\Components\AssetsComponent;
use StockForecastForWooCommerce\Config\PrefixConfig;
use StockForecastForWooCommerce\Components\AjaxComponent;
use StockForecastForWooCommerce\AdminUI\Theme\ThemeSwitcher;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AssetLoader
 *
 * Handles the registration and enqueueing of AdminUI assets including
 * core styles/scripts and third-party vendor libraries.
 *
 * @package StockForecastForWooCommerce\AdminUI\Assets
 * @version 1.0.0
 */
class AssetLoader extends AbstractSingleton
{
    /**
     * Whether assets have been registered.
     *
     * @var bool
     */
    private bool $registered = false;

    /**
     * Whether core assets have been enqueued.
     *
     * @var bool
     */
    private bool $coreEnqueued = false;

    /**
     * Core asset handle.
     */
    private const CORE_HANDLE = 'admin-ui';

    /**
     * Register the assets manager.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->registered) {
            return;
        }

        $this->registered = true;

        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
    }

    /**
     * Enqueue core AdminUI assets.
     *
     * @return void
     */
    public function enqueueAssets(): void
    {
        $this->enqueueCoreAssets();
    }

    /**
     * Enqueue core AdminUI assets.
     *
     * @return void
     */
    private function enqueueCoreAssets(): void
    {
        // Prevent duplicate enqueuing
        if ($this->coreEnqueued) {
            return;
        }

        $handle = AssetsComponent::getHandle(self::CORE_HANDLE);

        // Check if already enqueued by WordPress
        if (wp_style_is($handle) || wp_script_is($handle)) {
            $this->coreEnqueued = true;
            return;
        }

        // Register and enqueue styles
        AssetsComponent::registerStyle(self::CORE_HANDLE, 'css/admin.min.css');
        AssetsComponent::enqueueStyle(self::CORE_HANDLE);

        // Register and enqueue scripts
        AssetsComponent::registerScript(self::CORE_HANDLE, 'js/admin.min.js', ['jquery'], null, true);
        AssetsComponent::enqueueScript(self::CORE_HANDLE);

        // Localize script with config
        AssetsComponent::localizeScript(self::CORE_HANDLE, PrefixConfig::CONFIG_OBJECT, [
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => AjaxComponent::createNonce(),
            'restUrl'   => rest_url(PrefixConfig::SLUG . '/v1/'),
            'restNonce' => wp_create_nonce('wp_rest'),
            'theme'     => $this->getCurrentTheme(),
            'i18n'      => [
                'refresh'    => __('Refresh Forecasts', 'stock-forecast-for-woocommerce'),
                'refreshing' => __('Refreshing…', 'stock-forecast-for-woocommerce'),
                'started'    => __('Refresh started', 'stock-forecast-for-woocommerce'),
                'filters'    => [
                    'labels'        => [
                        'risk_level'          => __('Risk', 'stock-forecast-for-woocommerce'),
                        'product_type'        => __('Product Type', 'stock-forecast-for-woocommerce'),
                        'days_until_stockout' => __('Stockout', 'stock-forecast-for-woocommerce'),
                        'current_stock'       => __('Current Stock', 'stock-forecast-for-woocommerce'),
                        'daily_sales'         => __('Daily Sales', 'stock-forecast-for-woocommerce'),
                    ],
                    'values'        => [
                        'risk_level'          => [
                            'safe'         => __('Safe Stock', 'stock-forecast-for-woocommerce'),
                            'warning'      => __('Low Stock', 'stock-forecast-for-woocommerce'),
                            'critical'     => __('Critical Stock', 'stock-forecast-for-woocommerce'),
                            'out_of_stock' => __('Out of Stock', 'stock-forecast-for-woocommerce'),
                            'backordering' => __('Backorders', 'stock-forecast-for-woocommerce'),
                        ],
                        'product_type'        => [
                            'simple'    => __('Simple', 'stock-forecast-for-woocommerce'),
                            'variation' => __('Variation', 'stock-forecast-for-woocommerce'),
                        ],
                        'days_until_stockout' => [
                            7  => __('≤ 7 days', 'stock-forecast-for-woocommerce'),
                            14 => __('≤ 14 days', 'stock-forecast-for-woocommerce'),
                            30 => __('≤ 30 days', 'stock-forecast-for-woocommerce'),
                            60 => __('≤ 60 days', 'stock-forecast-for-woocommerce'),
                            90 => __('≤ 90 days', 'stock-forecast-for-woocommerce'),
                        ],
                        'current_stock'       => [
                            '0'            => __('0', 'stock-forecast-for-woocommerce'),
                            'range:1-10'   => __('1–10', 'stock-forecast-for-woocommerce'),
                            'range:11-50'  => __('11–50', 'stock-forecast-for-woocommerce'),
                            'range:51-100' => __('51–100', 'stock-forecast-for-woocommerce'),
                            'gte:100'      => __('100+', 'stock-forecast-for-woocommerce'),
                        ],
                        'daily_sales'         => [
                            '0'           => __('0', 'stock-forecast-for-woocommerce'),
                            'range:1-5'   => __('1–5', 'stock-forecast-for-woocommerce'),
                            'range:6-20'  => __('6–20', 'stock-forecast-for-woocommerce'),
                            'range:21-50' => __('21–50', 'stock-forecast-for-woocommerce'),
                            'gte:50'      => __('50+', 'stock-forecast-for-woocommerce'),
                        ]
                    ],
                    'remove_filter' => __('Remove filter', 'stock-forecast-for-woocommerce'),
                ],
            ],
        ]);

        $this->coreEnqueued = true;
    }

    /**
     * Get the current user's theme preference.
     *
     * @return string Theme name ('light' or 'dark').
     */
    public function getCurrentTheme(): string
    {
        return ThemeSwitcher::instance()->getCurrentTheme();
    }
}

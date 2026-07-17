<?php

namespace StockForecastForWooCommerce;

use StockForecastForWooCommerce\Admin\Notices\AdminNotices;
use StockForecastForWooCommerce\AdminUI\Assets\AssetLoader;
use StockForecastForWooCommerce\AdminUI\Theme\ThemeSwitcher;
use StockForecastForWooCommerce\Cache\CacheManager;
use StockForecastForWooCommerce\CLI\CLIManager;
use StockForecastForWooCommerce\Cron\CronManager;
use StockForecastForWooCommerce\Database\SchemaManager;
use StockForecastForWooCommerce\Menu\MenuManager;
use StockForecastForWooCommerce\Queue\QueueManager;
use StockForecastForWooCommerce\Services\Admin\Dashboard\DashboardManager;
use StockForecastForWooCommerce\Services\Admin\ProductForecast\ProductForecastManager;
use StockForecastForWooCommerce\Services\Admin\Settings\SettingsManager;
use StockForecastForWooCommerce\Services\Forecast\ForecastManager;
use StockForecastForWooCommerce\Utils\Logger;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin bootstrap class.
 *
 * @since      1.0.0
 * @package    StockForecastForWooCommerce
 */
final class Core
{
    /** The single instance of the class. */
    private static ?Core $instance = null;

    /** Core manager services (always loaded). */
    private array $coreServices = [
        SchemaManager::class,
        CacheManager::class,
        CronManager::class,
        CLIManager::class,
        QueueManager::class,
        ForecastManager::class,
    ];

    /** List of admin-specific service classes. */
    private array $adminServices = [
        MenuManager::class,
        AdminNotices::class,
        AssetLoader::class,
        ThemeSwitcher::class,
        DashboardManager::class,
        ProductForecastManager::class,
        SettingsManager::class,
    ];

    /** List of frontend-specific service classes. */
    private array $frontendServices = [];

    /** Get the instance via lazy initialization. */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
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

    /** Core constructor. */
    protected function __construct()
    {
        $this->setupHooks();
    }

    /** Setup WordPress action hooks used by the plugin. */
    private function setupHooks(): void
    {
        add_action('plugins_loaded', [$this, 'boot']);
    }

    /** Boot the plugin services. */
    public function boot(): void
    {
        Logger::init();

        $this->bootServices($this->coreServices);

        if (is_admin()) {
            $this->bootServices($this->adminServices);
        } else {
            $this->bootServices($this->frontendServices);
        }
    }

    /** Initialize an array of service classes. */
    private function bootServices(array $services): void
    {
        foreach ($services as $serviceClass) {
            if (!class_exists($serviceClass)) {
                continue;
            }

            if (method_exists($serviceClass, 'instance')) {
                $service = $serviceClass::instance();
            } else {
                $service = new $serviceClass();
            }

            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }
}
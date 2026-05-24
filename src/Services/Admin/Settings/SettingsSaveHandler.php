<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Admin\Notices\AdminNotices;
use StockForecastForWooCommerce\Utils\MenuUtils;
use StockForecastForWooCommerce\Utils\Request;
use StockForecastForWooCommerce\Utils\Security;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SettingsSaveHandler
 *
 * Handles saving of plugin settings via admin-post.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @version 1.0.0
 */
class SettingsSaveHandler
{
    /**
     * Process settings save request.
     *
     * @return void
     */
    public function handle(): void
    {
        if (Request::str('action', '', 'post') !== 'sffw_save_settings') {
            return;
        }

        if (!Security::hasCapability()) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'stock-forecast-for-woocommerce'));
        }

        if (Request::method() !== 'post') {
            wp_safe_redirect(MenuUtils::getUrl('settings'));
            exit;
        }

        $settings = Request::arr('settings', [], 'post');

        if (!$settings) {
            wp_safe_redirect(MenuUtils::getUrl('settings'));
            exit;
        }

        $saver = new SettingsSaver();
        $saver->save($settings);

        AdminNotices::success(
            sprintf(
                /* translators: %s: URL to the Product Forecast page */
                __(
                    'Please go to the <a href="%s"><strong>Product Forecast</strong></a> page and click <strong>Refresh Forecasts</strong> once to regenerate forecasts using the new settings.',
                    'stock-forecast-for-woocommerce'
                ),
                MenuUtils::getUrl('product-forecast')
            )
        )
            ->flash()
            ->setTitle(
                __('Your changes have been saved', 'stock-forecast-for-woocommerce')
            );

        wp_safe_redirect(MenuUtils::getUrl('settings'));
        exit;
    }
}
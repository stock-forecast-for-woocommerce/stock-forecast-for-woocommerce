<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Abstracts\AbstractAdminPage;
use StockForecastForWooCommerce\Config\PrefixConfig;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SettingsPage
 *
 * Admin settings page for the plugin.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @version 1.0.0
 */
class SettingsPage extends AbstractAdminPage
{
    /**
     * Get page header template path.
     *
     * @return string
     */
    protected function getPageHeaderTemplate(): string
    {
        return 'admin/pages/settings/header';
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    protected function getTemplate(): string
    {
        return 'admin/pages/settings/content';
    }

    /**
     * Provide template data.
     *
     * @return array
     */
    protected function getBodyContext(): array
    {
        $settingsData = new SettingsData();

        return [
            'settings'    => $settingsData->getSettings(),
            'sections'    => $settingsData->getSections(),
            'nonceAction' => PrefixConfig::nonce('settings'),
            'nonceName'   => PrefixConfig::nonce('settings_nonce'),
            'formAction'  => admin_url('admin-post.php'),
            'formName'    => PrefixConfig::BASE . '_save_settings',
        ];
    }
}
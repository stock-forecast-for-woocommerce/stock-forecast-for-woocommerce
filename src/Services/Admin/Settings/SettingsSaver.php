<?php

namespace StockForecastForWooCommerce\Services\Admin\Settings;

use StockForecastForWooCommerce\Config\PluginSettings;
use StockForecastForWooCommerce\Config\PrefixConfig;
use StockForecastForWooCommerce\Utils\OptionUtils;
use StockForecastForWooCommerce\Utils\Security;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SettingsSaver
 *
 * Handles saving of plugin settings from the admin panel.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @version 1.0.0
 */
class SettingsSaver
{
    /**
     * @var SettingsSanitizer
     */
    private SettingsSanitizer $sanitizer;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->sanitizer = new SettingsSanitizer();
    }

    /**
     * Save plugin settings after validating security and sanitizing input.
     *
     * @param array $input
     * @return void
     */
    public function save(array $input): void
    {
        $nonceAction = PrefixConfig::nonce('settings');
        $nonceField  = PrefixConfig::nonce('settings_nonce');

        if (!Security::verifyNonce($nonceAction, $nonceField)) {
            wp_die(esc_html__('Security check failed. Please refresh the page and try again.', 'stock-forecast-for-woocommerce'));
        }

        $settings = OptionUtils::getAllOptions();

        if (isset($input[PluginSettings::SECTION_FORECAST])) {
            $this->saveForecast($settings, $input[PluginSettings::SECTION_FORECAST]);
        }

        OptionUtils::updateAll($settings);
    }

    /**
     * Process and store forecast section settings.
     *
     * @param array $settings
     * @param array $data
     * @return void
     */
    private function saveForecast(array &$settings, array $data): void
    {
        $clean = $this->sanitizer->forecast($data);

        foreach ($clean as $key => $value) {
            $fullKey = OptionUtils::makeKey(PluginSettings::SECTION_FORECAST, $key);
            OptionUtils::setNestedValue($settings, $fullKey, $value);
        }
    }
}
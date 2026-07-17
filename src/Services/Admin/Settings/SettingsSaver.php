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
 * Saves plugin settings.
 *
 * @package StockForecastForWooCommerce\Services\Admin\Settings
 * @since   1.0.0
 */
class SettingsSaver
{
    /** Settings sanitizer instance. */
    private SettingsSanitizer $sanitizer;

    /** Initializes the settings saver. */
    public function __construct()
    {
        $this->sanitizer = new SettingsSanitizer();
    }

    /** Saves plugin settings. */
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

    /** Saves forecast section settings. */
    private function saveForecast(array &$settings, array $data): void
    {
        $clean = $this->sanitizer->forecast($data);

        foreach ($clean as $key => $value) {
            $fullKey = OptionUtils::makeKey(PluginSettings::SECTION_FORECAST, $key);
            OptionUtils::setNestedValue($settings, $fullKey, $value);
        }
    }
}
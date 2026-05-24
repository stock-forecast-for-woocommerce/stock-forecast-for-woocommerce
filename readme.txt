=== Stock Forecast for WooCommerce ===
Contributors: sitealerts
Tags: woocommerce, stock, forecasting, reports, analytics
Requires at least: 6.1
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Predicts when WooCommerce products will run out of stock using sales velocity. Real-time forecasts, color-coded risk levels, and variable product support.

== Description ==

Stock Forecast for WooCommerce analyzes recent sales velocity and predicts how many days remain before each product runs out of stock.

Instead of requiring manual checks or spreadsheets, the plugin automatically calculates Days Until Stockout for every simple product and variation. Forecasts update in real time whenever inventory changes, orders are placed, or product data is modified.

The plugin provides a dedicated admin menu named Stock Forecast with three sections:

- Dashboard: quick overview of critical, low, and safe products
- Product Forecast: a filterable, sortable table of all forecasts
- Settings: configure thresholds and forecasting behavior

All forecasting runs locally inside WordPress. No external services. No API keys. No data sharing.

Key features:

- Real-time stock forecasting based on sales velocity
- Supports simple and variable products with per-variation forecasting
- Color-coded risk levels (Safe, Low, Critical)
- Backorder-aware calculations
- Automatic full-store analysis on activation
- Manual Recalculate Forecast button
- Lightweight, optimized, and fast for large stores

== Installation ==

1. Upload the stock-forecast-for-woocommerce folder to the /wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Open the Stock Forecast menu. The plugin will automatically run the initial analysis

== Frequently Asked Questions ==

= Does the plugin support variable products? =
Yes. Each variation receives its own independent stockout forecast.

= When does the forecast update? =
Forecasts update automatically when stock quantity changes, orders are placed or completed, product data is updated, or during the daily cron run. You can also refresh all forecasts manually.

= Does this impact performance? =
No. The plugin uses optimized queries, batch processing, and lightweight logic.

= Does this plugin use external services? =
No. All forecasting runs locally. Nothing is sent to external servers.

= Does the plugin change my stock levels? =
No. It only forecasts and displays predictions. It never modifies inventory or orders.

= Is this plugin free? =
Yes. It is fully free and licensed under GPL-2.0-or-later.

== Changelog ==

= 1.0.0 =
* Initial release
* Real-time stockout forecasting based on sales velocity
* Support for simple and variable products
* Color-coded risk levels (Safe, Low, Critical)
* Dashboard with stat cards and critical stock highlights
* Filterable and sortable product forecast table
* Backorder-aware forecasting
* Manual Recalculate Forecast button
* Settings page with configurable thresholds
* Daily WP-Cron job for automated refresh
* Batch processing for large stores

== Upgrade Notice ==

= 1.0.0 =
Initial release. Predict stockouts before they happen with automated WooCommerce inventory forecasting.
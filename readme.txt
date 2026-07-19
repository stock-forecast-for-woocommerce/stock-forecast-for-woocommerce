=== Stock Forecast for WooCommerce ===
Contributors: zheynlab
Tags: woocommerce, stock, forecasting, reports, analytics
Requires at least: 6.1
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Predicts when products will run out of stock using sales velocity. Real‑time forecasts, color‑coded risk levels, and full variable product support.

== Description ==

= Never run out of stock again. =

Stockouts cost you money, trust, and momentum. Yet most store owners still rely on manual checks or gut feelings. Stock Forecast for WooCommerce replaces guesswork with precise, up‑to‑the‑minute predictions — showing exactly how many days until each product hits zero, across every simple product and every variation.

= Why Stock Forecast for WooCommerce? =

**Real‑time updates, zero effort**
Forecasts recalculate automatically whenever stock changes, an order is placed, or a product is edited. No need to click “refresh”. The numbers you see are always current, so your restocking decisions are based on live data — not last week’s spreadsheet.

**Clarity that drives action**
The dedicated Stock Forecast dashboard gives you an immediate overview of your inventory health. Products are grouped into three color‑coded risk levels — Safe, Low, and Critical — so you can spot trouble in seconds. Click through to the Product Forecast table for a filterable, sortable list of every SKU, complete with exact Days Until Stockout.

**Private by design, light on your server**
All forecasting logic runs inside your own WordPress database. No external APIs, no data sharing, no tracking. The plugin uses efficient batch processing that won’t slow down your admin, even on stores with thousands of products.

**Set it up in under a minute**
Install, activate, and the plugin immediately analyzes your entire product catalog. No setup wizards, no API keys, no complicated configuration. You’re ready to forecast from the first click.

= Features =

* **Real‑time forecasts** – Days Until Stockout for every simple product and variation, updated on every relevant event.
* **Color‑coded risk levels** – Safe (green), Low (amber), Critical (red) so you can prioritize instantly.
* **Variable product support** – Independent forecasting for each variation — no blending, no assumptions.
* **Backorder‑aware logic** – Automatically adjusts forecasts when backorders are allowed.
* **Full‑store scan on activation** – Get forecasts for every product the moment you activate the plugin.
* **Manual Recalculate button** – Refresh all forecasts with a single click whenever you want.
* **Daily WP‑Cron job** – Optional automated refresh to catch any missed updates.
* **Batch processing** – Optimized for large catalogs — no timeouts, no memory issues.

= Privacy & Performance by Design =

* **100% local forecasting** – Sales velocity calculations stay inside your database. We never see your inventory, orders, or customer data.
* **No personal data** – The plugin works only with product‑level aggregates. No visitor or customer information is ever collected or stored.
* **Ultra‑light footprint** – One admin menu, a few efficient queries, and zero front‑end code. No impact on your store’s page speed.
* **GDPR/CCPA friendly** – No cookies, no external connections, no consent banner needed.

= Who is Stock Forecast for WooCommerce for? =

* **Store owners tired of stockouts** – Know exactly when to reorder, before you lose sales.
* **Inventory managers** – Replace static spreadsheets with dynamic, automated forecasts.
* **WooCommerce agencies** – Give every client a clear view of inventory risks without extra tools.
* **High‑SKU merchants** – Scan hundreds of products at a glance and focus on the few that need attention.

== Installation ==

1. Download the plugin zip file.
2. In your WordPress dashboard, go to **Plugins → Add New → Upload Plugin**.
3. Choose the zip file and click **Install Now**, then **Activate**.
4. Visit the new **Stock Forecast** menu in your admin sidebar.

That’s it. No configuration pages to fill out, no API connections to set up. Stock Forecast for WooCommerce starts working immediately.

== Frequently Asked Questions ==

= Does the plugin support variable products? =
Yes. Each variation receives its own independent stockout forecast.

= When does the forecast update? =
Forecasts update automatically when stock quantity changes, orders are placed or completed, product data is updated, or during the daily cron run. You can also refresh all forecasts manually.

= Will the plugin order stock automatically? =
No. Stock Forecast for WooCommerce is a forecasting tool, not an inventory controller. It tells you when products will run out so you can reorder on your own terms.

= Does it affect my store’s front‑end speed? =
No. The plugin adds no front‑end code. All work happens in the admin area during page loads and via cron.

= Can I adjust the risk thresholds? =
Yes. The Settings page lets you define how many days of remaining stock count as Critical or Low.

= Does this plugin use external services? =
No. All forecasting runs locally. Nothing is sent to external servers.

= Will you add new forecasting features? =
Absolutely. We’re planning demand forecasting, seasonal trend analysis, and optional integration with supplier APIs — always keeping your data local.

= Is it free? =
Yes. The core plugin is and will remain free, licensed under GPL-2.0-or-later. A Pro version with advanced features may come in the future to support ongoing development.

== Screenshots ==

1. Dashboard with stat cards and critical stock highlights.
2. Product forecast table with risk indicators.
3. Settings page with configurable thresholds.
4. Manual recalculate button in action.

== Changelog ==

= 1.0.0 =
* Initial release
* Real‑time stockout forecasting based on sales velocity
* Support for simple and variable products
* Color‑coded risk levels (Safe, Low, Critical)
* Dashboard with stat cards and critical stock highlights
* Filterable and sortable product forecast table
* Backorder‑aware forecasting
* Manual Recalculate Forecast button
* Settings page with configurable thresholds
* Daily WP‑Cron job for automated refresh
* Batch processing for large stores

== Upgrade Notice ==

= 1.0.0 =
Initial release. Predict stockouts before they happen with automated WooCommerce inventory forecasting.

== Source Code ==
Source code and build tools are available at: https://github.com/stock-forecast-for-woocommerce/stock-forecast-for-woocommerce/

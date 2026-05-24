# Stock Forecast for WooCommerce

Stock Forecast for WooCommerce helps store owners predict when products will run out of stock before it becomes a problem.

It analyzes recent sales velocity and automatically calculates how many days remain before each product reaches zero stock, updating in real time whenever inventory changes.

Simple, useful insights directly inside the WordPress admin.

## Features

- Forecasts Days Until Stockout for simple and variable products
- Real-time updates when stock changes, orders complete, or product data updates
- Automatic full-store analysis on plugin activation
- Color-coded risk levels (Safe, Low, Critical)
- Filterable and sortable product forecast table
- Manual Recalculate Forecast button
- Supports backorders and edge cases with little or no sales data
- Lightweight, fast, and optimized for large stores

## Requirements

- WordPress 6.0 or higher
- WooCommerce 7.0 or higher
- PHP 7.4 or higher

## Installation

### Install from ZIP

1. Download the plugin ZIP.
2. Upload the stock-forecast-for-woocommerce directory to wp-content/plugins/
3. Activate Stock Forecast for WooCommerce from Plugins -> Installed Plugins
4. Go to Stock Forecast in the admin menu. The plugin will run the initial forecast automatically.


## Frequently Asked Questions

### Does the plugin support variable products?
Yes. Each variation gets its own independent stockout forecast.

### When does the forecast update?
Forecasts update automatically when stock changes, orders are placed, product data updates, or during the daily cron run.

### Does this affect performance?
No. The plugin is optimized for speed using batch processing and lightweight logic.

### Does the plugin use external services?
No. All processing happens locally in your WordPress installation.

### Does the plugin change my stock levels?
No. It only forecasts and displays predictions. It never modifies inventory or orders.
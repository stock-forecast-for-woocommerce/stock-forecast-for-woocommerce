# Changelog

## 1.0.0 – 2026-05-24

### Initial Release

#### Core Features
- Added top-level Stock Forecast admin menu
- Added automatic full forecast generation on plugin activation
- Added real-time forecast updates when stock changes, orders are placed, or product data updates
- Added Dashboard with stat cards and critical stock highlights
- Added Product Forecast page with sortable and filterable forecast data
- Added color-coded risk levels (Safe, Low, Critical)
- Added support for Simple and Variable products with per-variation forecasting
- Added stock depletion prediction using sales velocity and Days Until Stockout
- Added support for backorder-aware forecasting
- Added manual Recalculate Forecast button for full regeneration

#### Settings
- Added Settings page with configurable numeric fields:
- Sales History Period (days)
- Processing Batch Size
- Low Stock Warning Threshold (days)
- Critical Stock Threshold (days)

#### Technical Foundation
- Added optimized database table for forecast storage
- Added daily WP-Cron job for automated forecast refresh
- Added batch processing for improved performance on large stores
- Improved handling of zero sales, backorders, and negative stock

#### Notes
- No external API dependencies - all processing runs locally
- No automatic inventory modifications - only forecasts and displays predictions
- Designed for stores of all sizes with batch processing support
# Contributing to Stock Forecast for WooCommerce

We welcome bug reports, feature ideas, and pull requests.

## Reporting Bugs

Open an issue with:

- Clear title and steps to reproduce
- Expected vs. actual behavior
- WordPress, WooCommerce, theme, and PHP versions
- Any conflicting plugins

## Suggesting Features

Describe the problem, why it helps store owners, and how it should behave.

## Pull Requests

1. Fork the repo
2. Create a branch for your change
3. Follow WordPress and WooCommerce coding standards
4. Test your changes with WooCommerce enabled
5. Open a PR with a clear explanation

## Development Guidelines

- Keep performance a priority – lightweight queries, no long-running processes
- Ensure forecasting logic is efficient and batch-safe
- Real-time stock change hooks must remain performant
- Variable products and variations forecast independently and accurately
- Dashboard stat caching must continue to work
- No unnecessary dependencies or external services
- Maintain backward compatibility

## License

By contributing, you agree that your contributions will be licensed under the GPL-2.0-or-later license.
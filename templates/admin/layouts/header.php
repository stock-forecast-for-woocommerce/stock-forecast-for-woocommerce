<?php
/**
 * Admin Layout: Header
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables are locally scoped via include.
 *
 * @package StockForecastForWooCommerce\Templates\Admin\Layouts
 * @since   1.0.0
 *
 * @var string $title
 * @var string $titleLink
 * @var string $version
 * @var string $logoUrl
 * @var array $navItems
 * @var array $actions
 * @var string $theme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap sffw-wrap">
    <header class="sffw-header">
        <div class="sffw-header-container">
            <!-- Brand Section (Logo/Title) -->
            <div class="sffw-header-brand">
                <a href="<?php echo esc_url($titleLink); ?>" class="sffw-header-brand-link">
                    <?php if (!empty($logoUrl)) : ?>
                        <img
                            src="<?php echo esc_url($logoUrl); ?>"
                            alt="<?php echo esc_attr($title); ?>"
                            class="sffw-header-logo"
                        />
                    <?php endif; ?>
                    <span class="sffw-header-title"><?php echo esc_html($title); ?></span>
                    <span class="sffw-badge sffw-badge--secondary sffw-header-version">
                        v<?php echo esc_html($version); ?>
                    </span>
                </a>
            </div>

            <!-- Navigation Section -->
            <?php if (!empty($navItems)) : ?>
                <!-- Desktop Navigation (visible on large screens) -->
                <nav class="sffw-header-nav sffw-header-nav-desktop">
                    <ul class="sffw-header-nav-list">
                        <?php foreach ($navItems as $navItem) :
                            $itemClasses = ['sffw-header-nav-item'];
                            if (!empty($navItem['active'])) {
                                $itemClasses[] = 'sffw-active';
                            }
                            ?>
                            <li class="<?php echo esc_attr(implode(' ', $itemClasses)); ?>">
                                <a
                                    href="<?php echo esc_url($navItem['url'] ?? '#'); ?>"
                                    class="sffw-header-nav-link"
                                >
                                    <?php if (!empty($navItem['icon'])) : ?>
                                        <span class="sffw-dashicons <?php echo esc_attr($navItem['icon']); ?>"></span>
                                    <?php endif; ?>

                                    <span class="sffw-header-nav-text">
                                        <?php echo esc_html($navItem['label'] ?? ''); ?>
                                    </span>

                                    <?php if (!empty($navItem['badge'])) : ?>
                                        <span class="sffw-badge sffw-badge-primary sffw-badge-sm">
                                            <?php echo esc_html($navItem['badge']); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <!-- Mobile Navigation (toggle + dropdown) -->
                <div class="sffw-header-nav-wrapper">
                    <button
                        type="button"
                        class="sffw-header-toggle"
                        aria-label="<?php esc_attr_e('Toggle navigation', 'stock-forecast-for-woocommerce'); ?>"
                        aria-expanded="false"
                        aria-controls="sffw-header-nav"
                    >
                        <span class="sffw-header-toggle-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <nav class="sffw-header-nav" id="sffw-header-nav">
                        <ul class="sffw-header-nav-list">
                            <?php foreach ($navItems as $navItem) :
                                $itemClasses = ['sffw-header-nav-item'];
                                if (!empty($navItem['active'])) {
                                    $itemClasses[] = 'sffw-active';
                                }
                                ?>
                                <li class="<?php echo esc_attr(implode(' ', $itemClasses)); ?>">
                                    <a
                                        href="<?php echo esc_url($navItem['url'] ?? '#'); ?>"
                                        class="sffw-header-nav-link"
                                    >
                                        <?php if (!empty($navItem['icon'])) : ?>
                                            <span class="sffw-header-nav-icon <?php echo esc_attr($navItem['icon']); ?>"></span>
                                        <?php endif; ?>

                                        <span class="sffw-header-nav-text">
                                            <?php echo esc_html($navItem['label'] ?? ''); ?>
                                        </span>

                                        <?php if (!empty($navItem['badge'])) : ?>
                                            <span class="sffw-badge sffw-badge-primary sffw-badge-sm">
                                                <?php echo esc_html($navItem['badge']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- Actions Section -->
            <?php if (!empty($actions)) : ?>
                <div class="sffw-header-actions">
                    <?php foreach ($actions as $action) :
                        $type = $action['type'] ?? 'button';
                        $variant = $action['variant'] ?? 'primary';
                        $label = $action['label'] ?? '';
                        $url = $action['url'] ?? '#';
                        $icon = $action['icon'] ?? '';
                        $attrs = $action['attrs'] ?? [];

                        // Build class string
                        $btnClass = 'sffw-btn sffw-btn-' . esc_attr($variant);
                        if (!empty($action['class'])) {
                            $btnClass .= ' ' . esc_attr($action['class']);
                        }

                        // Build additional attributes string
                        $attrsStr = '';
                        foreach ($attrs as $attrKey => $attrVal) {
                            $attrsStr .= ' ' . esc_attr($attrKey) . '="' . esc_attr($attrVal) . '"';
                        }
                        ?>
                        <?php if ($type === 'link') : ?>
                        <a
                            href="<?php echo esc_url($url); ?>"
                            class="<?php echo esc_attr($btnClass); ?>"
                            <?php echo $attrsStr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        >
                            <?php if (!empty($icon)) : ?>
                                <span class="sffw-dashicons <?php echo esc_attr($icon); ?>"></span>
                            <?php endif; ?>
                            <span class="sffw-btn-text"><?php echo esc_html($label); ?></span>
                        </a>
                    <?php else : ?>
                        <button
                            type="button"
                            class="<?php echo esc_attr($btnClass); ?>"
                            <?php echo $attrsStr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        >
                            <?php if (!empty($icon)) : ?>
                                <span class="sffw-dashicons <?php echo esc_attr($icon); ?>"></span>
                            <?php endif; ?>
                            <span class="sffw-btn-text"><?php echo esc_html($label); ?></span>
                        </button>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="sffw-content-wrapper">

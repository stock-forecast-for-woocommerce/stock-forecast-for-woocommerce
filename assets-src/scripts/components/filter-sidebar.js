/**
 * Admin UI - Filter Sidebar Component
 *
 * Requires: namespace.js, config.js, helpers.js
 *
 * @version 1.0.0
 */
(function (window, document) {
    'use strict';

    const PREFIX_CONFIG = window.__PREFIX_CONFIG__;
    if (!PREFIX_CONFIG) return;

    const SFFW = window[PREFIX_CONFIG.namespace];
    if (!SFFW) return;

    const Helpers = SFFW.Helpers;
    const Config = SFFW.Config;
    if (!Helpers || !Config) return;

    const FilterSidebar = {

        sidebar: null,
        openButton: null,
        closeButton: null,
        overlay: null,
        panel: null,
        form: null,
        resetButton: null,
        badge: null,
        focusTrapActive: false,

        init: function () {

            this.sidebar = document.getElementById(SFFW.cssClass('filter-sidebar'));
            this.openButton = document.getElementById(SFFW.cssClass('filter-toggle'));

            if (!this.sidebar) return;

            this.panel = this.sidebar.querySelector(SFFW.selector('filter-sidebar__panel'));
            this.closeButton = this.sidebar.querySelector(SFFW.selector('filter-sidebar__close'));
            this.overlay = this.sidebar.querySelector(SFFW.selector('filter-sidebar__overlay'));

            this.form = document.getElementById(SFFW.cssClass('forecast-filters'));
            this.resetButton = document.getElementById(SFFW.cssClass('filter-reset'));

            this.badge = document.querySelector(SFFW.selector('filter-count'));

            this.syncFormFromURL();
            this.bindEvents();
            this.updateBadge();
            this.renderActiveFilters();
        },

        /* ------------------------------------------------------------
         * Sync form values with URL so badge & fields load correctly
         * ------------------------------------------------------------ */
        syncFormFromURL: function () {

            if (!this.form) return;

            const params = new URLSearchParams(window.location.search);

            this.form.querySelectorAll('input, select, textarea').forEach(el => {
                const name = el.name;
                if (!name) return;

                const val = params.get(name);
                if (val === null) return;

                if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = (el.value === val);
                } else {
                    el.value = val;
                }
            });
        },

        bindEvents: function () {

            if (this.openButton) {
                this.openButton.addEventListener('click', () => {
                    this.open();
                });
            }

            if (this.closeButton) {
                this.closeButton.addEventListener('click', () => {
                    this.close();
                });
            }

            if (this.overlay) {
                this.overlay.addEventListener('click', () => {
                    this.close();
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.sidebar.classList.contains('is-active')) {
                    this.close();
                }
            });

            /* -----------------------------
             * Filters: input + submit
             * ----------------------------- */
            if (this.form) {

                this.form.addEventListener('input', () => {
                    this.updateBadge();
                });

                this.form.addEventListener('submit', (e) => {

                    e.preventDefault();

                    const data = new FormData(this.form);
                    const params = {paged: null};

                    data.forEach((value, key) => {
                        params[key] = value || null;
                    });

                    Helpers.URL.updateParams(params);

                });
            }

            /* -----------------------------
             * Reset Filters
             * ----------------------------- */
            if (this.resetButton) {
                this.resetButton.addEventListener('click', () => {

                    const params = {paged: null};

                    this.form.querySelectorAll('[name]').forEach(el => {
                        params[el.name] = null;
                    });

                    Helpers.URL.updateParams(params);

                });
            }
        },

        /* ------------------------------------------------------------
         * Badge counter
         * ------------------------------------------------------------ */
        updateBadge: function () {

            if (!this.form || !this.badge) return;

            const inputs = this.form.querySelectorAll('input, select, textarea');
            let count = 0;

            inputs.forEach(el => {

                if (!el.name) return;

                if ((el.type === 'checkbox' || el.type === 'radio') && el.checked) {
                    count++;
                    return;
                }

                if (el.tagName === 'SELECT' && el.value !== '') {
                    count++;
                    return;
                }

                if ((el.type === 'text' || el.type === 'number') && el.value.trim() !== '') {
                    count++;
                }

            });

            this.badge.setAttribute(SFFW.dataAttr('count'), String(count));
            this.badge.textContent = String(count);
        },

        /* ------------------------------------------------------------
         * Active Filters
         * ------------------------------------------------------------ */
        renderActiveFilters: function () {

            const container = document.getElementById(SFFW.cssClass('active-filters'));
            if (!container) return;

            const params = new URLSearchParams(window.location.search);

            const i18n = SFFW.Config.getI18n();
            const filters = (i18n && i18n.filters) ? i18n.filters : {};

            const labels = filters.labels || {};
            const valuesMap = filters.values || {};
            const removeText = filters.remove_filter || 'Remove filter';

            container.innerHTML = '';

            params.forEach((value, key) => {

                if (!labels[key] || value === '') return;

                let displayValue = value;

                if (valuesMap[key] && valuesMap[key][value]) {
                    displayValue = valuesMap[key][value];
                }

                const chip = document.createElement('div');
                chip.className = SFFW.cssClass('filter-chip');

                const label = document.createElement('span');
                label.textContent = `${labels[key]}: ${displayValue}`;

                const button = document.createElement('button');
                button.className = SFFW.cssClass('filter-chip__remove');
                button.setAttribute(SFFW.dataAttr('filter'), key);
                button.setAttribute(SFFW.dataAttr('value'), value);

                button.setAttribute(
                    'aria-label',
                    `${removeText} (${labels[key]}: ${displayValue})`
                );

                const icon = document.createElement('span');
                icon.className = SFFW.cssClass('icon--close');
                icon.setAttribute('aria-hidden', 'true');

                button.appendChild(icon);

                chip.appendChild(label);
                chip.appendChild(button);

                container.appendChild(chip);

            });

            container.querySelectorAll(SFFW.dataSelector('filter')).forEach(btn => {

                btn.addEventListener('click', () => {

                    const key = btn.getAttribute(SFFW.dataAttr('filter'));

                    const update = {};
                    update[key] = null;

                    Helpers.URL.updateParams(update);

                });

            });

        },

        /* ------------------------------------------------------------
         * Sidebar open/close + focus trap
         * ------------------------------------------------------------ */
        open: function () {

            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

            document.body.style.paddingRight = scrollbarWidth + 'px';

            this.sidebar.classList.add('is-active');
            document.body.classList.add(SFFW.cssClass('sidebar-open'));

            const firstInput = this.panel.querySelector('input, select, button');
            if (firstInput) firstInput.focus();

            this.trapFocus();
        },

        close: function () {

            this.sidebar.classList.remove('is-active');
            document.body.classList.remove(SFFW.cssClass('sidebar-open'));

            document.body.style.paddingRight = '';
        },

        trapFocus: function () {

            if (!this.panel || this.focusTrapActive) return;

            this.focusTrapActive = true;

            const focusable = this.panel.querySelectorAll(
                'button, a[href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );

            if (!focusable.length) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            this.panel.addEventListener('keydown', (e) => {

                if (e.key !== 'Tab') return;

                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }

                if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            });
        }
    };

    /* Init component */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            FilterSidebar.init();
        });
    } else {
        FilterSidebar.init();
    }

    SFFW.FilterSidebar = FilterSidebar;

})(window, document);
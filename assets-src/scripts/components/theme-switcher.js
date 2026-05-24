/**
 * Admin UI - Theme Switcher
 *
 * Requires: namespace.js, config.js, helpers.js
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

    const ThemeSwitcher = {

        wrapper: null,
        storageKey: null,
        toggleBtn: null,

        /* ------------------------------------------------------------
         * Init
         * ------------------------------------------------------------ */
        init: function () {

            this.storageKey = SFFW.storageKey('theme');
            this.wrapper = document.querySelector(SFFW.selector('ui'));
            this.toggleBtn = document.getElementById(SFFW.cssClass('theme-toggle'));

            this.bindEvents();
            this.applyStoredTheme();
        },

        /* ------------------------------------------------------------
         * Events
         * ------------------------------------------------------------ */
        bindEvents: function () {

            if (this.toggleBtn) {
                this.toggleBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggle();
                });
            }

        },

        /* ------------------------------------------------------------
         * Theme Logic
         * ------------------------------------------------------------ */
        getTheme: function () {

            const stored = Helpers.storageGet(this.storageKey);
            if (stored === 'light' || stored === 'dark') {
                return stored;
            }

            if (this.wrapper) {
                if (this.wrapper.classList.contains(SFFW.cssClass('theme-light'))) return 'light';
                if (this.wrapper.classList.contains(SFFW.cssClass('theme-dark'))) return 'dark';
            }

            return 'light';
        },

        setTheme: function (theme) {

            if (theme !== 'light' && theme !== 'dark') {
                theme = 'light';
            }

            if (this.wrapper) {
                this.wrapper.classList.remove(SFFW.cssClass('theme-light'), SFFW.cssClass('theme-dark'));
                this.wrapper.classList.add(SFFW.cssClass(`theme-${theme}`));
            }

            Helpers.storageSet(this.storageKey, theme);

            this.saveToServer(theme);

        },

        toggle: function () {
            const nextTheme = this.getTheme() === 'light' ? 'dark' : 'light';
            this.setTheme(nextTheme);
        },

        /* ------------------------------------------------------------
         * Apply stored theme on load
         * ------------------------------------------------------------ */
        applyStoredTheme: function () {

            const stored = Helpers.storageGet(this.storageKey);
            const theme = (stored === 'light' || stored === 'dark')
                ? stored
                : this.getTheme();

            if (stored && this.wrapper) {
                this.wrapper.classList.remove(SFFW.cssClass('theme-light'), SFFW.cssClass('theme-dark'));
                this.wrapper.classList.add(SFFW.cssClass(`theme-${theme}`));
            }

        },

        /* ------------------------------------------------------------
         * Save Theme to Server
         * ------------------------------------------------------------ */
        saveToServer: function (theme) {

            if (typeof window.fetch !== 'function') return;

            const ajaxUrl = Config.getAjaxUrl();
            const nonce = Config.getNonce();
            if (!ajaxUrl || !nonce) return;

            const formData = new FormData();
            formData.append('action', SFFW.ajaxAction('switch_theme'));
            formData.append('security', nonce);
            formData.append('theme', theme);

            window.fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }).catch(() => {
            });
        }
    };

    /* ------------------------------------------------------------
     * Init component
     * ------------------------------------------------------------ */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            ThemeSwitcher.init();
        });
    } else {
        ThemeSwitcher.init();
    }

    SFFW.ThemeSwitcher = ThemeSwitcher;

})(window, document);
/**
 * Admin UI - Product Forecast page
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

    const ProductForecast = {
        refreshBtn: null,
        isProcessing: false,
        originalText: '',
        iconEl: null,
        textEl: null,

        init() {
            this.refreshBtn = document.getElementById(SFFW.cssClass('refresh-forecasts'));
            if (!this.refreshBtn) return;

            this.iconEl = this.refreshBtn.querySelector(SFFW.selector('icon--refresh'));
            this.textEl = this.refreshBtn.querySelector(SFFW.selector('refresh-forecasts-text'));

            if (this.textEl) {
                this.originalText = this.textEl.textContent.trim();
            }

            this.bindEvents();
        },

        bindEvents() {
            this.refreshBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.startRefreshProcess().then(() => {
                });
            });
        },

        async startRefreshProcess() {
            if (this.isProcessing) return;

            const i18n = Config.getI18n();
            this.isProcessing = true;

            this.setButtonState({
                disabled: true,
                text: i18n.refreshing || 'Refreshing...',
                spinning: true
            });

            try {
                await this.sendRefreshRequest();

                this.setButtonState({
                    disabled: true,
                    text: i18n.refreshStarted || 'Refresh started',
                    spinning: false,
                    success: true
                });

                setTimeout(() => this.resetButton(i18n), 10000);

            } catch (error) {
                console.error('Refresh request failed:', error);
                this.resetButton(i18n);
            }
        },

        sendRefreshRequest() {
            const ajaxUrl = Config.getAjaxUrl();
            const nonce = Config.getNonce();

            if (!ajaxUrl || !nonce) {
                return Promise.reject('Missing configuration');
            }

            const formData = new FormData();
            formData.append('action', SFFW.ajaxAction('refresh_forecasts'));
            formData.append('security', nonce);

            return window.fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
        },

        setButtonState({disabled, text, spinning, success}) {
            const spinClass = SFFW.cssClass('spin');

            this.refreshBtn.disabled = !!disabled;

            if (this.iconEl) {
                this.iconEl.classList.toggle(spinClass, !!spinning);
            }

            if (this.textEl) {
                this.textEl.textContent = text;
            }
        },

        resetButton(i18n) {
            this.isProcessing = false;

            const spinClass = SFFW.cssClass('spin');

            this.refreshBtn.disabled = false;

            if (this.iconEl) {
                this.iconEl.classList.remove(spinClass);
            }

            this.setButtonState({
                disabled: false,
                text: i18n.refreshForecasts || this.originalText,
                spinning: false,
                success: false
            });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => ProductForecast.init());
    } else {
        ProductForecast.init();
    }

    SFFW.ProductForecast = ProductForecast;

})(window, document);
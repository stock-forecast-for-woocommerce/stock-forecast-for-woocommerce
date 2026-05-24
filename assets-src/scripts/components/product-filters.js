/**
 * Admin UI - Product Filters Component
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

    const ProductFilters = {

        search: null,

        init: function () {
            this.search = document.getElementById(SFFW.cssClass('forecast-search'));

            this.bindEvents();
        },

        bindEvents: function () {
            /** Search */
            if (this.search) {
                this.search.addEventListener('keydown', (e) => {

                    if (e.key !== 'Enter') return;

                    const target = Helpers.getElement(e.target);

                    Helpers.URL.updateParams({
                        paged: null,
                        search: target.value
                    });

                });
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            ProductFilters.init();
        });
    } else {
        ProductFilters.init();
    }

    SFFW.ProductFilters = ProductFilters;

})(window, document);
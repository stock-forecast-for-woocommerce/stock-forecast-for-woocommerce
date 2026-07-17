/**
 * Admin UI - Pagination Component
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

    const Pagination = {

        select: null,

        init: function () {
            this.select = document.getElementById(SFFW.cssClass('filter-per-page'));

            this.bindEvents();
        },

        bindEvents: function () {

            /** Change per_page */
            if (this.select) {
                this.select.addEventListener('change', (e) => {

                    const target = Helpers.getElement(e.target);

                    Helpers.URL.updateParams({
                        paged: null,
                        per_page: target.value
                    });
                });
            }

            /** Enter on paged input */
            document.addEventListener('keydown', (e) => {

                if (e.key !== 'Enter') return;

                const target = Helpers.getElement(e.target);

                if (!target.classList.contains(SFFW.cssClass('paged'))) return;

                Helpers.URL.updateParams({
                    paged: target.value
                });

            });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            Pagination.init();
        });
    } else {
        Pagination.init();
    }

    SFFW.Pagination = Pagination;

})(window, document);
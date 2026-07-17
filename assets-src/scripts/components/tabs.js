/**
 * Admin UI - Tabs Component
 *
 * Requires: namespace.js, helpers.js
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
    if (!Helpers) return;

    const Tabs = {

        /* ------------------------------------------------------------
         * Init
         * ------------------------------------------------------------ */
        init: function () {
            this.bindEvents();
        },

        /* ------------------------------------------------------------
         * Bind Events
         * ------------------------------------------------------------ */
        bindEvents: function () {

            document.addEventListener('click', (e) => {

                const target = Helpers.getElement(e.target);
                if (!target) return;

                const tab = target.closest('[' + SFFW.dataAttr('toggle') + '="tab"]');
                if (tab) {
                    e.preventDefault();
                    Tabs.show(tab);
                }
            });
        },

        /* ------------------------------------------------------------
         * Show a tab
         * ------------------------------------------------------------ */
        show: function (tab) {

            if (!tab) return;

            const tabList = tab.closest(
                SFFW.selector('nav-tabs') + ', ' + SFFW.selector('tab-nav-pills')
            );

            const targetId = tab.getAttribute(SFFW.dataAttr('target')) || tab.getAttribute('href');
            if (!targetId) return;

            // Deactivate all tabs in the same list
            if (tabList) {
                tabList.querySelectorAll('[' + SFFW.dataAttr('toggle') + '="tab"]').forEach((t) => {
                    t.classList.remove(SFFW.cssClass('active'));
                    t.setAttribute('aria-selected', 'false');
                });
            }

            // Activate clicked tab
            tab.classList.add(SFFW.cssClass('active'));
            tab.setAttribute('aria-selected', 'true');

            // Find tab content container
            const tabContent = document.querySelector(targetId);
            if (!tabContent) return;

            const tabPaneContainer =
                tabContent.closest(SFFW.selector('tab-content')) || tabContent.parentElement;

            // Hide all panes in container
            if (tabPaneContainer) {
                tabPaneContainer.querySelectorAll(SFFW.selector('tab-pane')).forEach((pane) => {
                    pane.classList.remove(SFFW.cssClass('show'), SFFW.cssClass('active'));
                });
            }

            // Show target pane
            tabContent.classList.add(SFFW.cssClass('show'), SFFW.cssClass('active'));
        },

        /* ------------------------------------------------------------
         * Get active tab inside a list (string selector or element)
         * ------------------------------------------------------------ */
        getActiveTab: function (tabList) {

            if (typeof tabList === 'string') {
                tabList = document.querySelector(tabList);
            }

            if (!tabList) return null;

            return tabList.querySelector(
                '[' + SFFW.dataAttr('toggle') + '="tab"].' + SFFW.cssClass('active')
            );
        },

        /* ------------------------------------------------------------
         * Get active pane inside a container (string selector or element)
         * ------------------------------------------------------------ */
        getActivePane: function (tabContent) {

            if (typeof tabContent === 'string') {
                tabContent = document.querySelector(tabContent);
            }

            if (!tabContent) return null;

            return tabContent.querySelector(
                SFFW.selector('tab-pane') + '.' + SFFW.cssClass('active')
            );
        }
    };

    /* ------------------------------------------------------------
     * Init Component
     * ------------------------------------------------------------ */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            Tabs.init();
        });
    } else {
        Tabs.init();
    }

    SFFW.Tabs = Tabs;

})(window, document);
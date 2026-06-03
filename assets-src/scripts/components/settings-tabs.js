/**
 * Admin UI - Settings Tabs Component
 *
 * Handles vertical menu tab switching for settings page.
 * Requires: namespace.js, helpers.js, config.js
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

    const SettingsTabs = {

        sections: null,
        navButtons: null,
        storageKey: null,

        /**
         * Initialize component.
         */
        init: function () {
            this.sections = document.querySelectorAll(SFFW.selector('settings__section'));
            this.navButtons = document.querySelectorAll(SFFW.selector('nav-item'));

            if (!this.sections.length || !this.navButtons.length) return;

            this.storageKey = SFFW.storageKey('active_settings_section');

            this.bindEvents();
            this.restoreActiveTab();
        },

        /**
         * Bind click events on navigation buttons.
         */
        bindEvents: function () {
            this.navButtons.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const sectionId = btn.getAttribute(SFFW.dataAttr('section'));
                    if (sectionId) this.activateSection(sectionId);
                });
            });

            window.addEventListener('hashchange', () => this.handleHashChange());
            this.handleHashChange();
        },

        /**
         * Show the selected section and update active button.
         *
         * @param {string} sectionId
         */
        activateSection: function (sectionId) {
            // Hide all sections
            this.sections.forEach((section) => {
                section.classList.add(SFFW.cssClass('d-none'));
            });

            // Show selected section
            const activeSection = document.getElementById(
                SFFW.cssClass('section-' + sectionId)
            );

            if (activeSection) {
                activeSection.classList.remove(SFFW.cssClass('d-none'));
            }

            // Update active class on buttons
            this.navButtons.forEach((btn) => {
                btn.classList.remove(SFFW.cssClass('is-active'));
                if (btn.getAttribute(SFFW.dataAttr('section')) === sectionId) {
                    btn.classList.add(SFFW.cssClass('is-active'));
                }
            });

            // Save to localStorage
            Helpers.storageSet(this.storageKey, sectionId);

            // Update URL hash without scrolling
            if (history.replaceState) {
                history.replaceState(null, null, '#' + sectionId);
            }
        },

        /**
         * Restore last active section from localStorage or hash.
         */
        restoreActiveTab: function () {
            let sectionId = Helpers.storageGet(this.storageKey);

            // If no stored value, check URL hash
            if (!sectionId && window.location.hash) {
                sectionId = window.location.hash.substring(1);
            }

            // Validate that section exists
            const valid = sectionId && document.getElementById(SFFW.cssClass('section-' + sectionId));
            if (!valid && this.navButtons.length) {
                sectionId = this.navButtons[0].getAttribute(SFFW.dataAttr('section'));
            }

            if (sectionId) this.activateSection(sectionId);
        },

        /**
         * Handle hash change (e.g., user clicks back/forward).
         */
        handleHashChange: function () {

            const hash = window.location.hash.substring(1);

            if (!hash) return;

            const targetBtn = document.querySelector(
                SFFW.selector('nav-item') +
                '[' + SFFW.dataAttr('section') + '="' + hash + '"]'
            );

            if (targetBtn) {
                this.activateSection(hash);
            }

        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            SettingsTabs.init();
        });
    } else {
        SettingsTabs.init();
    }

    // Expose globally
    SFFW.SettingsTabs = SettingsTabs;

})(window, document);
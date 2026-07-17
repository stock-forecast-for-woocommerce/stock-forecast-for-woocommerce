/**
 * Admin UI - Admin Notices Component
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

    const AdminNotices = {

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

                const btn = target.closest(SFFW.selector('notice') + ' ' + SFFW.selector('notice-close'));
                if (!btn) return;

                const notice = btn.closest(SFFW.selector('notice'));
                if (!notice) return;

                const noticeId = notice.getAttribute('id');

                this.dismissNotice(noticeId);
                notice.remove();
            });
        },

        /* ------------------------------------------------------------
         * AJAX Dismiss Notice
         * ------------------------------------------------------------ */
        dismissNotice: function (noticeId) {

            if (!noticeId || typeof window.fetch !== 'function') return;

            const ajaxUrl = Config.getAjaxUrl();
            const nonce = Config.getNonce();
            if (!ajaxUrl || !nonce) return;

            const formData = new FormData();
            formData.append('action', SFFW.ajaxAction('dismiss_notice'));
            formData.append('notice_id', noticeId);
            formData.append('security', nonce);

            window.fetch(ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            }).catch(() => {
            });
        }
    };

    /* Init component */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            AdminNotices.init();
        });
    } else {
        AdminNotices.init();
    }

    SFFW.AdminNotices = AdminNotices;

})(window, document);
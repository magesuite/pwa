define([
    'jquery',
    'uiComponent',
], function ($, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            benefitsContentSelector: '.cs-pwa-guide__content-benefits',
            benefitsTabSelector: '.cs-pwa-guide__tabs-item--benefits',
            installationContentSelector: '.cs-pwa-guide__content-installation',
            installationTabSelector: '.cs-pwa-guide__tabs-item--installation',
            activeTabCssClass: 'cs-pwa-guide__tabs-item__active',
            headerHeight: 112
        },

        initialize: function () {
            this._super();

            const _this = this;

            $(document).on('scroll', function() {
                if($(this).scrollTop() >= 0
                    && ($(this).scrollTop() < $(_this.benefitsContentSelector).height() + $(_this.benefitsContentSelector).offset().top - _this.headerHeight)) {
                    $(_this.benefitsTabSelector).addClass(_this.activeTabCssClass);
                } else {
                    $(_this.benefitsTabSelector).removeClass(_this.activeTabCssClass);
                }

                if($(this).scrollTop() >= $(_this.installationContentSelector).offset().top - _this.headerHeight - 30
                    && ($(this).scrollTop() < $(_this.installationContentSelector).height() + $(_this.installationContentSelector).offset().top - _this.headerHeight - 30)) {
                    $(_this.installationTabSelector).addClass(_this.activeTabCssClass);
                } else {
                    $(_this.installationTabSelector).removeClass(_this.activeTabCssClass);
                }
            });

            if (this.getMobileOperatingSystem() !== 'unknown') {
                $(`${this.installationContentSelector}--${this.getMobileOperatingSystem()}`).show();
            }
        },

        getMobileOperatingSystem: function () {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;

            if (/android/i.test(userAgent) && !/windows phone/i.test(userAgent)) {
                return "android";
            }

            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                return "ios";
            }

            return "unknown";
        }
    })
});

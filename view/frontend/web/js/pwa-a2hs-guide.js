
define([
    'jquery',
    'uiComponent',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, Component, modal) {
    'use strict';

    return Component.extend({
        defaults: {
            showAgainTime: 0, // Always show guide, even after user clicked close before. Set 2592000000 for 30 days
            headerIOS: $.mage.__('Install this webapp on your %device%'),
            descriptionIOS: $.mage.__('Tap at the bottom of the banner on the Thank You page on <span class="cs-pwa-a2hs-guide__install-icon"></span> and then tap "Go to Home Screen".'),
            headerAndroid: $.mage.__('Install this webapp on your phone'),
            descriptionAndroid: $.mage.__('Tap <b>Install</b>. <br>Follow the instructions on the screen.'),
            headerDesktop: $.mage.__('Install this webapp on your desktop'),
            descriptionDesktop: $.mage.__('Click "Install" <span class="cs-pwa-a2hs-guide__install-icon"></span> in the upper-right corner of the address bar. Follow the on-screen instructions to install the PWA.'),
            declineText: $.mage.__('No, thank you'),
            showmoreText: $.mage.__('Show PWA guide'),
        },

        initialize: function () {
            this._super();

            this.currentDevice = 'desktop';

            if (this.isIOS()) {
                this.currentDevice = 'ios';
            } else if (this.isAndroid()) {
                this.currentDevice = 'android';
            }

            this.headerIOS = this.headerIOS.replace('%device%', this._getIOSdeviceName());

            return this;
        },

        /**
         * @return {Object}
         */
        initObservable: function () {
            this._super()
                .observe(['canShowGuide', 'isDescriptionVisible']);

                var self = this;

            if (this._canShowGuide()) {
                // Set Timeout to wait till all scripts are download and extecuted in order to make animation smooth
                setTimeout(function() {
                    this.canShowGuide(true);
                }.bind(this), 1200)
            }

            this.isDescriptionVisible(false);
            if (this.getCurrentDevice() === 'desktop') {
                this.isDescriptionVisible(true);
            }

            return this;
        },

        getCurrentDevice: function() {
            return this.currentDevice;
        },

        toggleCollapse: function () {
            this.isDescriptionVisible(!this.isDescriptionVisible());
        },

        /**
         * Checks if device is iOS-powered
         * @returns {boolean}
         */
        isIOS: function() {
            return [
                'iPad Simulator',
                'iPhone Simulator',
                'iPod Simulator',
                'iPad',
                'iPhone',
                'iPod'
            ].includes(navigator.platform)
            || (navigator.userAgent.includes('Mac') && 'ontouchend' in document);
        },

        /**
         * Checks if device is Android-powered
         * @returns {boolean}
         */
        isAndroid: function() {
            const userAgent = navigator.userAgent.toLowerCase();
            return userAgent.indexOf("android") > -1;
        },

        isDesktop: function() {
            return !this.isIOS() && !this.isAndroid();
        },

        /**
         * Checks if A2HS banner can be shown.
         * To evaluate to true, localStorage last-decline time must be greater than defaults.showAgainTime or empty, and the web app can't be already installed.
         * @returns {boolean} - whether banner should be shown or not
         */
        _canShowGuide: function() {
            var isStandalone = ('standalone' in window.navigator) && (window.navigator.standalone),
                lastDeclinedTime = localStorage.getItem(
                    'magesuite-pwa-guide-declined-time'
                );

            return !isStandalone && (!lastDeclinedTime || new Date().getTime() - this.showAgainTime >= lastDeclinedTime);
        },

        /**
         * Returns readable iOS-powered device name. Either iPhone or iPad.
         * @returns {string}
         */
        _getIOSdeviceName: function() {
            if (/iphone/.test(window.navigator.userAgent.toLowerCase())) {
                return 'iPhone';
            } else if (
                /ipad/.test(window.navigator.userAgent.toLowerCase()) || (navigator.maxTouchPoints && navigator.maxTouchPoints > 2 && /MacIntel/.test(navigator.platform))) {
                return 'iPad';
            }

            return '';
        },

        closePanel: function() {
            this.canShowGuide(false);
            this._cancelClickHandler();
        },

        /**
         * Set info in storage that pwa guide was closed. Show it again after options.showAgainTime
         * @private
         */
        _cancelClickHandler: function () {
            localStorage.setItem(
                'magesuite-pwa-guide-declined-time',
                new Date().getTime()
            );
        },
    });
});

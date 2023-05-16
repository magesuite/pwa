define([
    'jquery',
    'uiComponent',
    'mage/translate'
], function ($, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            allowedDisplaysBeforeHiding: 3,
            hidingTime: 10 * 24 * 60 * 60 * 1000, // 10 days
            showTimeout: 10 * 1000, // 10 seconds
            text: $.mage.__('Tap the "Share" icon <span class="cs-pwa-a2hs-guide__install-icon"></span> at the bottom of the screen and select "Go to Home Screen".'),
        },

        initialize: function () {
            this._super();

            if (!this.isIOS() || !this.canShowPrompt()) {
                return;
            }

            this.allowedDisplaysBeforeHiding = parseInt(this.allowedDisplaysBeforeHiding, 10);
            this.hidingTime = parseInt(this.hidingTime, 10);
            this.showTimeout = parseInt(this.showTimeout, 10);

            this.displayCountInfoName = 'magesuite-ios-pwa-prompt-display-count';
			this.declinedTimeInfoName = 'magesuite-ios-pwa-prompt-declined-time';

            this.lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;

            this.handleDisplaysCount();
            setTimeout(this.showPrompt.bind(this), this.showTimeout);

            return this;
        },

        showPrompt: function() {
            $('body').append(`<div class="cs-pwa-a2hs-guide cs-pwa-a2hs-guide--ios-prompt">${this.text}<span class="cs-pwa-a2hs-guide__close"></span></div>`);
            this.$prompt = $('.cs-pwa-a2hs-guide--ios-prompt');
            this.attachEvents();
        },

        attachEvents: function() {
            this.$prompt.on('click', () => {
                this.minimizePrompt();
            });
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

        canShowPrompt: function() {
            const isStandalone = ('standalone' in window.navigator) && (window.navigator.standalone);
            const lastDeclinedTime = parseInt(localStorage.getItem('magesuite-ios-pwa-prompt-declined-time'), 10);
            return !isStandalone && ((!lastDeclinedTime || lastDeclinedTime === NaN) || new Date().getTime() + this.showTimeout >= lastDeclinedTime + this.hidingTime);
        },

        handleDisplaysCount: function () {
            const countInfo = localStorage.getItem(this.displayCountInfoName);
            const currentCount = countInfo ? parseInt(JSON.parse(countInfo).count, 10) + 1 : 0;

            if (currentCount >= this.allowedDisplaysBeforeHiding) {
                this.setHiddenOnNextDisplay();
                localStorage.setItem(this.displayCountInfoName, JSON.stringify({count: 0, lastSeen: new Date()}));
            } else {
                localStorage.setItem(this.displayCountInfoName, JSON.stringify({count: currentCount, lastSeen: new Date()}));
            }
		},

        minimizePrompt: function() {
            this.$prompt.addClass('minimized');
            this.setScrollListener();
        },

        setScrollListener: function() {
            window.addEventListener('scroll', () => {
            const st = window.pageYOffset || document.documentElement.scrollTop;
            if (st > this.lastScrollTop) {
                this.$prompt.hide();
            } else if (st < this.lastScrollTop) {
                this.$prompt.show();
            }

            this.lastScrollTop = st <= 0 ? 0 : st;
            }, false);
        },

        setHiddenOnNextDisplay: function() {
            localStorage.setItem(
                this.declinedTimeInfoName,
                new Date().getTime()
            );
        },
    });
});

define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'text!MageSuite_Pwa/template/pwa-notification.html',
    'text!MageSuite_Pwa/template/a2hs-ios-guide.html',
    'mage/validation',
    'jquery-ui-modules/widget',
    'jquery/jquery-storageapi',
], function ($, alert, template, iosGuideTemplate) {
    'use strict';

    $.widget('magesuite.pwaNotification', {
        options: {
            popupTpl: template,
            iosGuideTpl: iosGuideTemplate,
            titleText: 'Add to homescreen headline',
            contentText: 'Now add the MageSuite Demo Shop to your homescreen with one click',
            acceptButtonText: 'Add to homescreen',
            modalClass: '',
            iOSbanner: {
                iosTransferIconPath: 'images/icons/ios/transfer.svg',
                modalTitle: '',
                modalContent: 'Install this webapp on your %device%: tap %icon% and then "Add to homescreen"',
                showButtonText: false,
                closeButtonText: 'Close',
                closeButtonIcon: 'images/icons/close.svg',
                modalClass: '',
            },
            showAgainTime: 2592000000, // 30 days
        },

        /**
         * Prevent the mini-infobar from appearing on mobile. Stash the event so it can be triggered later.
         * @private
         */
        _create: function () {
            var canShowBanner = this._canShowBanner();

            window.addEventListener(
                'beforeinstallprompt',
                function (event) {
                    event.preventDefault();

                    if (canShowBanner) {
                        this._showCustomPwaNotification(event);
                    }
                }.bind(this)
            );

            if (this.isIOS() && canShowBanner) {
                this._showIOSguide();
            }
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
         * Checks if A2HS banner can be shown. 
         * To evaluate to true localStorage last-decline time must be greater than options.showAgainTime or be empty AND webapp isn't already installed 
         * @returns {boolean} - whether banner should be shown or not
         */
        _canShowBanner: function() {
            var isStandalone = ('standalone' in window.navigator) && (window.navigator.standalone),
                lastDeclinedTime = localStorage.getItem(
                    'magesuite-pwa-notification-declined-time'
                );

            return !isStandalone && (!lastDeclinedTime || new Date().getTime() - this.options.showAgainTime >= lastDeclinedTime);
        },

        /**
         * Template for PWA custom notification is based on custom modal template.
         * It can be changed by overriding MageSuite_Pwa/template/pwa-notification.html file or by specifying path to own template options
         * @private
         */
        _showCustomPwaNotification: function (deferredPrompt) {
            var deferredPrompt = deferredPrompt;
            var $widget = this;

            alert({
                popupTpl: this.options.popupTpl,
                clickableOverlay: false,
                title: this.options.titleText,
                content: this.options.contentText,
                modalClass: this.options.modalClass,
                actions: {
                    cancel: this._cancelClickHandler,
                },
                buttons: [
                    {
                        text: this.options.acceptButtonText,
                        click: function () {
                            $widget._acceptClickHandler(deferredPrompt);
                            this.closeModal(true);
                        },
                    },
                ],
            });
        },

        /**
         * Returnss readable iOS-powered device name. Either iPhone or iPad.
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

        /**
         * Shows A2HS Guide tooltip to iOS users.
         * The only button is "Close" since on iOS we can't make an A2HS action for the user, we can only guide him how to do it.
         * 3s after tip is opened, a 'touchend' event is set up to close it and set localStorage entry
         */
        _showIOSguide: function() {
            var $widget = this,
                imgPath = require.toUrl(this.options.iOSbanner.iosTransferIconPath),
                modalContent = this.options.iOSbanner.modalContent
                    .replace('%device%', this._getIOSdeviceName())
                    .replace('%icon%', '<img class="inline-svg cs-pwa-notification__ios-transfer-icon" width="18" height="24" src="' + imgPath + '" alt="">');

            alert({
                popupTpl: this.options.iosGuideTpl,
                clickableOverlay: false,
                title: this.options.iOSbanner.modalTitle,
                content: modalContent,
                modalClass: 'device-' + this._getIOSdeviceName(),
                buttons: [
                    {
                        text: this.options.iOSbanner.closeButtonText,
                        class: this.options.iOSbanner.showButtonText,
                        textClass: this.options.iOSbanner.showButtonText ? '' : 'cs-visually-hidden',
                        showButtonIcon: this.options.iOSbanner.closeButtonIcon.length,
                        iconPath: require.toUrl(this.options.iOSbanner.closeButtonIcon), 
                        click: function () {
                            $widget._cancelClickHandler();
                            this.closeModal(true);
                        },
                    },
                ],
                opened: function(evt) {
                    $('body').removeClass('_has-modal');

                    setTimeout(function() {
                        window.addEventListener(
                            'touchend', 
                            function() {
                                $widget._cancelClickHandler();

                                $(evt.currentTarget)
                                    .find('button')
                                    .trigger('click');
                            }, 
                            { once: true }
                        );
                    }, 3000);
                }
            });
        },

        /**
         * Show native prompt
         * @private
         */
        _acceptClickHandler: function (deferredPrompt) {
            deferredPrompt.prompt();

            /**
             * Some actions can be also done based on user's choice
             
             * deferredPrompt.userChoice.then(function (choiceResult) {
             *   if (choiceResult.outcome !== 'accepted') {
             *       // some action can be placed here
             *   } else {
             *       // some action can be placed here
             *   }
             * });
             */
        },

        /**
         * Set info in storage that pwa notification was closed. Show it again after options.showAgainTime
         * @private
         */
        _cancelClickHandler: function () {
            localStorage.setItem(
                'magesuite-pwa-notification-declined-time',
                new Date().getTime()
            );
        },
    });

    return $.magesuite.pwaNotification;
});

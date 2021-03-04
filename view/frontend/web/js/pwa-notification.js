define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'text!MageSuite_Pwa/template/pwa-notification.html',
    'jquery-ui-modules/widget',
    'mage/validation',
    'jquery/jquery-storageapi',
], function ($, alert, template) {
    'use strict';

    $.widget('magesuite.pwaNotification', {
        options: {
            popupTpl: template,
            titleText: 'Add to homescreen headline',
            contentText: 'Now add the MageSuite Demo Shop to your homescreen with one click',
            acceptButtonText: 'Add to homescreen',
            modalClass: '',
            showAgainTime: 604800000, // 1 week
        },

        /**
         * Prevent the mini-infobar from appearing on mobile. Stash the event so it can be triggered later.
         * @private
         */
        _create: function () {
            window.addEventListener(
                'beforeinstallprompt',
                function (event) {
                    event.preventDefault();

                    var lastDeclinedTime = localStorage.getItem(
                        'magesuite-pwa-notification-declined-time'
                    );

                    if (
                        !lastDeclinedTime ||
                        new Date().getTime() - this.options.showAgainTime >=
                            lastDeclinedTime
                    ) {
                        this._showCustomPwaNotification(event);
                    }
                }.bind(this)
            );
        },

        /**
         * Template for PWA custom notification is based on custom modal template.
         * It can be changed by overriding MageSuite_Pwa/template/pwa-notification.html file or by specifying path to own template options
         * @private
         */
        _showCustomPwaNotification: function (deferredPrompt) {
            var deferredPrompt = deferredPrompt;
            var pwaWidget = this;

            alert({
                popupTpl: this.options.popupTpl,
                clickableOverlay: false,
                title: this.options.titleText,
                content: this.options.contentText,
                modalClass: this.options.modalClass,
                actions: {
                    always: function () {
                        // some action can be placed here
                    },
                    cancel: this._cancelClickHandler,
                },
                buttons: [
                    {
                        text: this.options.acceptButtonText,
                        click: function () {
                            pwaWidget._acceptClickHandler(deferredPrompt);
                            this.closeModal(true);
                        },
                    },
                ],
            });
        },

        /**
         * Show native prompt
         * @private
         */
        _acceptClickHandler: function (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function (choiceResult) {
                if (choiceResult.outcome !== 'accepted') {
                    // some action can be placed here
                } else {
                    // some action can be placed here
                }
            });
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

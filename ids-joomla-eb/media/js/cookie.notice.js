/**
 * Cookie Notice JS
 * @author Alessandro Benoit
 */
;
(function () {

    "use strict";

    /**
     * Store current instance
     */
    var instance;

    /**
     * Defaults values
     * @type object
     */
    var defaults = {
        'noticeText': 'N&oacute;s utilizamos cookies para aprimorar a experi&ecirc;ncia de navega&ccedil;&atilde;o em nosso site. Coletamos informa&ccedil;&otilde;es b&aacute;sicas sobre a(s) visita(s) realizadas(s). <br> Se voc&ecirc; concorda, clique em "Ciente".',
        'learnMoreLinkEnabled': false,
        'learnMoreLinkHref': '/cookie-banner-information.html',
        'learnMoreLinkText': 'Pol&iacute;tica de cookies',
        'buttonText': 'Ciente',
        'expiresIn': 30
    };

    /**
     * Initialize cookie notice on DOMContentLoaded
     * if not already initialized with alt params
     */
    document.addEventListener('DOMContentLoaded', function () {
        if (!instance) {
            new cookieNoticeJS();
        }
    });

    /**
     * Constructor
     */
    window.cookieNoticeJS = function () {

        // If an instance is already set stop here
        if (instance !== undefined) {
            return;
        }

        // Set current instance
        instance = this;

        // If cookies are not supported or notice cookie is already set
        if (!testCookie() || getNoticeCookie()) {
            return;
        }

        // Extend default params
        var params = extendDefaults(defaults, arguments[0] || {});

        // Create notice
        var notice = createNotice();
        var message = createMessage(params.noticeText);

        var learnMoreLink;

        if (params.learnMoreLinkEnabled) {
            learnMoreLink = createLearnMoreLink(params.learnMoreLinkText, params.learnMoreLinkHref);
        }

        // Create dismiss button
        var dismissButton = createDismissButton(params.buttonText);

        // Dismiss button click event
        dismissButton.addEventListener('click', function (e) {
            e.preventDefault();
            setDismissNoticeCookie(parseInt(params.expiresIn + "", 10) * 60 * 1000 * 60 * 24);
            removeElementOut(notice);
        });

        var buttons = document.createElement('div');
        buttons.className = 'br-modal-footer actions justify-content-end';

        if (!!learnMoreLink) {
            buttons.appendChild(learnMoreLink);
        }

        buttons.appendChild(dismissButton);

        var noticeContainer = document.createElement('div');
        noticeContainer.className = 'container-fluid p-1 p-2xh';
        var noticeCard = document.createElement('div');
        noticeCard.className = 'br-card';
        var noticeModal = document.createElement('div');
        noticeModal.className = 'br-modal';

        noticeContainer.appendChild(message);
        noticeContainer.appendChild(buttons);
        noticeCard.appendChild(noticeContainer);
        noticeModal.appendChild(noticeCard);
        notice.appendChild(noticeModal);

        // Append notice to the DOM
        document.body.appendChild(notice);
    };

    /**
     * Test if cookies are enabled
     * @returns {boolean}
     */
    function testCookie() {
        document.cookie = 'testCookie=1';
        return document.cookie.indexOf('testCookie') != -1;
    }

    /**
     * Test if notice cookie is there
     * @returns {boolean}
     */
    function getNoticeCookie() {
        return document.cookie.indexOf('cookie_notice') != -1;
    }

    /**
     * Create notice
     * @returns {HTMLElement}
     */
    function createNotice() {

        var notice = document.createElement('div');

        notice.setAttribute('id', 'cookieNotice');
        notice.setAttribute('tabindex', '-1');
        notice.className = 'br-cookiebar default';

        return notice;
    }

        /**
     * Create message
     * @param messageText
     * @returns {HTMLElement}
     */
    function createMessage(messageText) {

        var messageWrapper = document.createElement('div');
        messageWrapper.className = 'wrapper p-2xh';

        var modalBody = document.createElement('div');
        modalBody.className = 'br-modal-body';

        var message = document.createElement('p');
        message.className = 'info-text';
        message.innerHTML = messageText + '&nbsp;';

        modalBody.appendChild(message);
        messageWrapper.appendChild(modalBody);

        return messageWrapper;
    }

    /**
     * Create dismiss button
     * @param message
     * @returns {HTMLElement}
     */
    function createDismissButton(message) {

        var dismissButton = document.createElement('a');

        // Dismiss button
        dismissButton.href = '#';
        dismissButton.innerHTML = message;

        dismissButton.className = 'br-button primary small';

        return dismissButton;

    }

    /**
     * Create dismiss button
     * @param learnMoreLinkText
     * @param learnMoreLinkHref
     * @returns {HTMLElement}
     */
    function createLearnMoreLink(learnMoreLinkText, learnMoreLinkHref) {

        var learnMoreLink = document.createElement('a');

        // Learn More button
        learnMoreLink.href = learnMoreLinkHref;
        learnMoreLink.innerHTML = learnMoreLinkText;
        learnMoreLink.target = '_blank';
        learnMoreLink.className = 'br-button secondary small';

        return learnMoreLink;

    }

    /**
     * Set sismiss notice cookie
     * @param expireIn
     */
    function setDismissNoticeCookie(expireIn) {
        var now = new Date(),
            cookieExpire = new Date();

        cookieExpire.setTime(now.getTime() + expireIn);
        document.cookie = "cookie_notice=1; expires=" + cookieExpire.toUTCString() + "; path=/;";
    }

    /**
     * Fade a given element out
     * @param element
     */
    function removeElementOut(element) {
        element.parentNode.removeChild(element);
    }

    /**
     * Utility method to extend defaults with user options
     * @param source
     * @param properties
     * @returns {*}
     */
    function extendDefaults(source, properties) {
        var property;
        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                if (typeof source[property] === 'object') {
                    source[property] = extendDefaults(source[property], properties[property]);
                } else {
                    source[property] = properties[property];
                }
            }
        }
        return source;
    }



}());

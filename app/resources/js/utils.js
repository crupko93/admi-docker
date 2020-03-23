import { helpers } from 'vuelidate/lib/validators';
import store       from '~/store/index';

export let Utils = {
    /**
     * Resolve a route placeholder with given parameter list
     * @param  {String} placeholder route placeholder to resolve
     * @param  {Object} params      (optional) parameters to fill
     * @return {String}             the resolved route
     */
    route (placeholder, params = {}) {
        _.each(params, (value, key) => {
            placeholder = placeholder.replace(`#${key}#`, value);
        });

        return placeholder;
    },

    /**
     * Redirect to a resolved route placeholder
     * @param {String} placeholder route placeholder to resolve
     * @param {Object} params      (optional) parameters to fill
     */
    go (placeholder, params = {}) {
        window.location.href = Utils.route(placeholder, params);
    },

    /**
     * Convert a 24-hour format time string to 12-hour format (`H:i` => `h:i a`)
     * @param  {String} time 24-hour time string to convert
     * @return {String}      12-hour time string
     */
    hour24to12 (time) {
        if (_.isEmpty(time)) return time;

        let H = +time.substr(0, 2),
            h = (H % 12 || 12) + '';

        return h.padStart(2, '0') + time.substr(2, 3) + ((H < 12 || H === 24) ? ' am' : ' pm');
    },

    /**
     * Handle a standard error response (using Snotify)
     * @param {Object} e error object
     */
    standardErrorResponse (e) {
        console.log(e);
        e = e.response.data;

        let err = 'An unexpected error occurred';

        if (e.message) {
            err = e.message;
        } else if (e.errors && !_.isEmpty(e.errors)) {
            err = e.errors[Object.keys(e.errors)[0]][0];
        }

        Snotify.error(err);
    },

    /**
     * Return card brand icon identifier based on card brand name
     * @param  {String} brand card brand name
     * @return {String}       card brand icon identifier
     */
    cardBrandIcon (brand) {
        if (!brand) return 'stripe';

        switch (brand) {
        case 'American Express': return 'amex';
        case 'Diners Club'     : return 'diners-club';
        case 'Discover'        : return 'discover';
        case 'JCB'             : return 'jcb';
        case 'MasterCard'      : return 'mastercard';
        case 'Visa'            : return 'visa';
        case 'Visa Electron'   : return 'visa-electron';
        default                : return 'stripe';
        }
    },

    /**
     * Return the formatted size
     * @param {Number} x
     */
    fileSize (x) {
        const units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        let l = 0,
            n = parseInt(x, 10) || 0;

        while (n >= 1024 && ++l) n = n / 1024;

        return n.toFixed(n >= 10 || l < 1 ? 0 : 1) + ' ' + units[l];
    },

    /**
     * Alert the user about the uploaded file size
     * @param {String} fileName
     * @param {Number} fileSize
     * @param {Number} maximumAllowedSize
     */
    alertFileSize (fileName, fileSize, maximumAllowedSize = 20971520) {
        const span = document.createElement('span');

        if (fileSize <= maximumAllowedSize) return false;

        span.innerHTML = 'The maximum file size for upload is ' +
            this.fileSize(maximumAllowedSize) + '. <br> File: <strong class="error--text">' +
            fileName + '</strong> <br> Size: ' + this.fileSize(fileSize) + '.';

        swal({
            title  : 'Error!',
            icon   : 'error',
            content: {
                element: span
            },
            className: 'swal-error',
            button   : {
                text     : 'Ok',
                className: 'outlined'
            }
        });

        return true;
    },

    /**
     * Custom validators
     * @type {Object}
     */
    validators: {
        /**
         * Wrapper for phone input component validation method
         */
        phone (ref) {
            return function (value) {
                return !helpers.req(value) || this.$refs[ref].isValid();
            };
        },

        /**
         * Verifies that given value does not contain whitespace characters
         */
        noSpaces: () => value => !/\s/.test(value || ''),

        noSpecialChars: () => value => {
            if (typeof value === 'undefined' || value === null || value === '') {
                return true;
            }

            return /^^[a-zA-Z\s-_]+$/.test(value);
        },

        positiveNumber: () => value => {
            if (typeof value === 'undefined' || value === null || value === '') {
                return true;
            }

            return /^[0-9]*$/.test(value);
        }
    },

    /**
     * Clean text of HTML tags and space chars
     * @param {String} text
     */
    clearHTMLAndSpaceChars (text) {
        // Clear HTML tags
        let result = text.replace(/(<([^>]+)>)/g,'');

        // Avoid double spaced TinyMCE `&nbsp;` additions
        return result.replace(/&nbsp;/g, ' ');
    },

    copyToClipboard (text) {
        let temp = $('<input>');

        $('body').append(temp);
        temp.val(text).select();

        if (document.queryCommandSupported('copy')) document.execCommand('copy');

        temp.remove();

        Snotify.success('Registration URL copied to clipboard!');

    },

    /**
     * Check if form is valid, ignoring optional validator fails
     * @return {Boolean}
     */
    isValidForm (validation) {
        if (!validation.$invalid) return true;
        if (validation.acceptedTerms.$invalid) return false;

        let valid = true;

        _.each(validation.form.$params, (v, key) => {
            _.each(validation.form[key].$params, (validator, name) => {
                // Skip optional validators
                if (_.startsWith(name, 'optional')) return;

                // Break if any required validator fails
                if (validation.form[key][name] === false) {
                    valid = false;
                    return false;
                }
            });
        });

        return valid;
    },

    /**
     * Check if the user has the appropriate permission
     * @return {Boolean}
     */
    hasPermissionTo (permission) {
        let user = store.getters['auth/user'];
        let check = false;
        if (user && user.permissions) {
            if (Array.isArray(permission)) {
                let checks = [];
                for (let i in permission) {
                    checks[i] = user.permissions.includes(permission);
                    check     = check || checks[i];
                }
            } else {
                check = user.permissions.includes(permission);
            }
        }

        return check;
    }
};

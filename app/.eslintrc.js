module.exports = exports = {
    env: {
        browser : true,
        commonjs: true,
        es6     : true
    },

    extends: ['eslint:recommended', 'plugin:vue/essential'],

    parserOptions: {
        sourceType: 'module'
    },

    rules: {
        indent                       : ['error', 4],
        'linebreak-style'            : ['error', 'unix'],
        quotes                       : ['error', 'single'],
        semi                         : ['error', 'always'],
        'space-before-function-paren': ["error", "always"],
    },

    globals: {
        API    : true,
        Vue    : true,
        _      : true,
        Bus    : true,
        l      : true,
        $      : true,
        jQuery : true,
        Utils  : true,
        swal   : true,
        console: true,
        app    : true,
        axios  : true,
        Snotify: true,
    }
};

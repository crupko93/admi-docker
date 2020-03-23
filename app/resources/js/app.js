if (window.Vue === undefined) {
    window.Vue = require('vue');
}

require('./components/bootstrap');

// Imports (JS)
import { API }                      from './api/api';
import App                          from '$comp/App';
import 'babel-polyfill';
import i18n                         from '~/plugins/i18n';
import '~/plugins/index';
import router                       from '~/router/index';
import store                        from '~/store/index';
import swal                         from 'sweetalert';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import { Utils }                    from './utils';
import Vuetify                      from 'vuetify';
import Vuelidate                    from 'vuelidate';
import vuetify                      from '~/plugins/vuetify';

// Global declarations
window.API   = API;
window.Utils = Utils;
window._     = require('lodash');
window.swal  = require('sweetalert');

if (Laravel.env === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug    = false;
    Vue.config.silent   = true;
}

Vue.use(Vuelidate);
Vue.use(Snotify, {
    global: {
        newOnTop         : false,
        preventDuplicates: true,
    },
    toast: {
        position: SnotifyPosition.rightTop
    }
});

// Vue prototype declarations
Vue.prototype.$utils = window.Utils;

window.Bus = new Vue({
    i18n,
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');

export const app = Bus;

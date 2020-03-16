if (window.Vue === undefined) {
    window.Vue = require('vue');
}

require('./components/bootstrap');

// Imports (JS)
import { API }                      from './api/api';
import swal                         from 'sweetalert';
import Vuetify                      from 'vuetify';
import Vuelidate                    from 'vuelidate';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import 'babel-polyfill';
import router                       from '~/router/index';
import store                        from '~/store/index';
import App                          from '$comp/App';
import vuetify                      from '~/plugins/vuetify';
import '~/plugins/index';

// Global declarations
window.API   = API;
window.Utils = require('./utils.js');
window._      = require('lodash');
window.swal   = require('sweetalert');

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
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');

export const app = Bus;

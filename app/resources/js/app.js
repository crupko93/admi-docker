if (window.Vue === undefined) {
    window.Vue = require('vue');

    window.Bus = new Vue();
}

require('./components/bootstrap');
/**
 * Define the Vue filters.
 */
require('./helpers/filters');

// Imports (JS)
import { API }                      from './api/api';
import App                          from '$comp/App';
import 'babel-polyfill';
import i18n                         from '~/plugins/i18n';
import '~/plugins/index';
import moment                       from 'moment';
import router                       from '~/router/index';
import store                        from '~/store/index';
import swal                         from 'sweetalert';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import { Utils }                    from './utils';
import Vuetify                      from 'vuetify';
import Vuelidate                    from 'vuelidate';
import vuetify                      from '~/plugins/vuetify';

// Global declarations
window.API            = API;
window.Utils          = Utils;
window.swal           = require('sweetalert');
window.currencySymbol = '€';

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
Vue.prototype._      = require('lodash');
Vue.prototype.moment = moment;

export default Bus = new Vue({
    i18n,
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');

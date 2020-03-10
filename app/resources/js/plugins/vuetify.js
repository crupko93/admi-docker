import Vue                                 from 'vue';
import Vuetify, { VSnackbar, VBtn, VIcon } from 'vuetify/lib';
import VuetifyToast                        from 'vuetify-toast-snackbar';

Vue.use(Vuetify, {
    components: {
        VSnackbar,
        VBtn,
        VIcon
    }
});
Vue.use(VuetifyToast);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary  : '#6459c4',
                secondary: '#2ec647',
                accent   : '#7E18E6',
                error    : '#FF5252',
                info     : '#2196F3',
                success  : '#4CAF50',
                warning  : '#FFC107'
            },
            dark: {
                primary  : '#6459c4',
                secondary: '#2ec647',
                accent   : '#7E18E6',
                error    : '#FF5252',
                info     : '#2196F3',
                success  : '#4CAF50',
                warning  : '#FFC107'
            },
        },
    }
});

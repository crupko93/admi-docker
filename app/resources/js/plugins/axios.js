import store from '~/store/index';
import router from '~/router/index';
import { app } from '~/app';
import axios from 'axios';

const instance = axios.create({
    baseURL: '/api'
});

instance.interceptors.request.use(config => {
    config.headers['X-Requested-With'] = 'XMLHttpRequest';

    const token = store.getters['auth/token'];
    if (token) {
        config.headers['Authorization'] = 'Bearer ' + token;
    }

    return config;
}, error => {
    return Promise.reject(error);
});

instance.interceptors.response.use(response => {
    return response;
}, async error => {
    if (store.getters['auth/token']) {
    // TODO: Find more reliable way to determine when Token state
        if (error.response.status === 401 && error.response.data.message === 'Token has expired') {
            API.auth.refresh()
                .then(response => {
                    store.dispatch('auth/saveToken', response);
                })
                .catch(Utils.standardErrorResponse);
            return axios.request(error.config);
        }

        if (error.response.status === 401 ||
      (error.response.status === 500 && (
          error.response.data.message === 'Token has expired and can no longer be refreshed' ||
        error.response.data.message === 'The token has been blacklisted'
      ))
        ) {
            store.dispatch('auth/destroy');
            router.push({ name: 'login' });
        }
    }

    return Promise.reject(error);
});

export default instance;

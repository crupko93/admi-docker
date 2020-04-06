import * as types from '../mutation-types';
import Cookies    from 'js-cookie';

/**
 * Initial state
 */
export const state = {
    user : null,
    token: Cookies.get('token')
};

/**
 * Mutations
 */
export const mutations = {
    [types.SET_USER] (state, {user}) {
        state.user = user;
    },

    [types.LOGOUT] (state) {
        state.user  = null;
        state.token = null;
        Cookies.remove('token');

    },

    [types.FETCH_USER_FAILURE] (state) {
        state.user = null;
        Cookies.remove('token');

    },

    [types.SET_TOKEN] (state, {token}) {
        state.token = token;
        // window.localStorage.setItem('token', token);
        Cookies.set('token', token, {expires: 365});
    }
};

/**
 * Actions
 */
export const actions = {
    saveToken ({commit}, payload) {
        commit(types.SET_TOKEN, payload);
    },

    async fetchUser ({commit}) {
        try {
            const { data } =  await API.users.me();
            commit(types.SET_USER, data);
            console.log('setUser');
            console.log(state.token);
        } catch (e) {
            commit(types.FETCH_USER_FAILURE);
        }
    },

    setUser ({commit}, payload) {
        commit(types.SET_USER, payload);
    },

    async logout ({commit}) {
        await API.auth.logout();
        commit(types.LOGOUT);
    },

    destroy ({commit}) {
        commit(types.LOGOUT);
    }
};

/**
 * Getters
 */
export const getters = {
    user : state => state.user,
    check: state => state.user !== null,
    token: state => state.token
};

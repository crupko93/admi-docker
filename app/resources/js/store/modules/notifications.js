import * as types from '../mutation-types';

/**
 * Initial state
 */
export const state = {
    announcements: [],
    system       : []
};

/**
 * Mutations
 */
export const mutations = {
    [types.SET_NOTIFICATIONS] (state, {announcements, system}) {
        state.announcements = announcements;
        state.system        = system;
    },
};

/**
 * Actions
 */
export const actions = {
    setNotifications ({commit}, payload) {
        commit(types.SET_NOTIFICATIONS, payload);
    }
};

/**
 * Getters
 */
export const getters = {
    announcements: state => state.announcements,
    system       : state => state.system
};

import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        toasts: []
    },
    mutations: {
        PUSH_TOAST(state, payload)
        {
            state.toasts.push(payload);
        }
    },
    getters: {
        TOASTS: state => state.toasts
    }
});
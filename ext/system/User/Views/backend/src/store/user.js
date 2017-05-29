import Vue from 'vue';
import Store from '@/store';

Store.registerModule('user', {
    namespaced: true,
    state: {
        items: [],
        groups: [],
        user: {}
    },
    mutations: {
        set(state, payload)
        {
            state.items = payload;
        },
        setGroups(state, payload)
        {
            state.groups = payload;
        },
        setUser(state, payload)
        {
            state.user = payload;
        }
    },
    actions: {
        load(context, payload)
        {
            Vue.http.get('api/user/list')
                .then(response => {
                    context.commit('set', response.body.data);
                    context.commit('setGroups', response.body.groups);

                    (payload || function() {})();
                });
        }
    },
    getters: {
        items:  state => state.items,
        groups: state => state.groups,
        user:   state => state.user
    }
});
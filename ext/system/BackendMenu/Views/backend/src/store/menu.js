import Vue from 'vue';
import Store from '@/store';

Store.registerModule('menu', {
    namespaced: true,
    state: {
        items: []
    },
    mutations: {
        set(state, payload)
        {
            state.items = payload;
        }
    },
    actions: {
        load(context, payload)
        {
            Vue.http.get('api/menu/list')
                .then(response => {
                    context.commit('set', response.data.data);
                });
        }
    },
    getters: {
        items: state => state.items
    }
});
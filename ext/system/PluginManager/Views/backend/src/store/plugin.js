import Vue from 'vue';
import Store from '@/store';

Store.registerModule('plugin', {
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
            Vue.http.get('api/plugin/list')
                .then(response => {
                    context.commit('set', response.data.data);

                    (payload || function() {})();
                });
        }
    },
    getters: {
        items: state => state.items
    }
});
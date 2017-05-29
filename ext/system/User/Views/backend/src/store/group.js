import Vue from 'vue';
import Store from '@/store';

Store.registerModule('group', {
    namespaced: true,
    state: {
        items: [],
        item: {}
    },
    mutations: {
        set(state, payload)
        {
            state.items = payload;
        },
        setItem(state, payload)
        {
            state.item = payload;
        }
    },
    actions: {
        load(context, payload)
        {
            Vue.http.get('api/group/list')
                .then(response => {
                    context.commit('set', response.body.data);

                    (payload || function() {})();
                });
        }
    },
    getters: {
        items: state => state.items,
        item:  state => state.item
    }
});
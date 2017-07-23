import Vue from 'vue';
import Store from '@/store';

Store.registerModule('page', {
    namespaced: true,
    state: {
        items: []
    },
    mutations: {
        set(state, payload)
        {
            payload.forEach(item => {
                item.editing = false;
            });

            state.items = payload;
        },
        add(state, payload)
        {
            let index = state.items.findIndex(item => item.id === payload.id);

            if (index !== -1)
            {
                state.items[index] = payload;
            }
            else
            {
                state.items.push(payload);
            }
        }
    },
    actions: {
        load(context, payload)
        {
            Vue.http.get('api/page/list')
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
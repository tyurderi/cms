import Vue from 'vue';
import Store from '@/store';

Store.registerModule('site', {
    namespaced: true,
    state: {
        items: []
    },
    mutations: {
        add(state, payload)
        {
            _.each(payload, (item) => {
                let index = state.items.findIndex(comparingItem => item.id === comparingItem.id);

                if (index === -1)
                {
                    state.items.push(item);
                }
                else
                {
                    state.items[index] = item;
                }
            });
        },
        set(state, payload)
        {
            state.items = payload;
        }
    },
    actions: {
        load(context, payload)
        {
            Vue.http.get('api/site/list')
                .then(response => {
                    context.commit('set', response.body.data);

                    (payload || function() {})();
                });
        }
    },
    getters: {
        items: state => state.items
    }
});
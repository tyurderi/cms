import Vue from 'vue';
import Store from '@/store';
import _ from 'lodash';

Store.registerModule('group', {
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
            Vue.http.get('api/group/list')
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
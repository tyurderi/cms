import Vue from 'vue';
import Store from '@/store';
import _ from 'lodash';

Store.registerModule('permission/value', {
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
            payload = payload || {};

            Vue.http.post('api/permissionCategory/listValues', { groupID: payload.groupID })
                .then(response => {
                    context.commit('set', response.body.data);

                    (payload.done || function() {})();
                });
        },
    },
    getters: {
        items: state => state.items
    }
});
import Vue from 'vue';
import Store from '@/store';
import _ from 'lodash';

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
        },
        remove(state, payload)
        {
            state.items.splice(state.items.indexOf(payload), 1);
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
        },
        save(context, payload)
        {
            let data = _.pick(payload, [
                'id',
                'parentID',
                'domainID',
                'type',
                'label'
            ]);

            return new Promise((accept, reject) => {
                Vue.http.post('api/page/save', data).then(accept, reject);
            })
        },
        remove(context, payload)
        {
            context.commit('remove', payload);

            return new Promise((accept, reject) => {
                Vue.http.post('api/page/remove', _.pick(payload, ['id'])).then(accept, reject);
            })
        }
    },
    getters: {
        items: state => state.items
    }
});
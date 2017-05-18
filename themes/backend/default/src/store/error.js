import Store from './index';

Store.registerModule('error', {
    namespaced: true,
    state: {
        errors: []
    },
    mutations: {
        push(state, payload)
        {
            state.errors.push(payload)
        }
    },
    actions: {
        push(context, payload)
        {
            context.commit('push', {
                solved: false,
                data: payload
            })
        }
    },
    getters: {
        hasError(state)
        {
            return state.errors.findIndex(error => error.solved === false) !== -1;
        },
        currentError(state)
        {
            return state.errors.find(error => error.solved === false);
        },
        total(state)
        {
            return state.errors.filter(error => error.solved === false).length;
        }
    }
});
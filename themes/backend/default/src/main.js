import { registerComponents } from './util';

import Vue from 'vue'
import App from './App'

import router from './router.js'
import store from './store';

import Http from 'vue-resource';
import Events from 'vue-events';

Vue.use(Http);
Vue.use(Events);

registerComponents([
    require('@/components/input/Checkbox')
]);

// Call plugin logic
import '@Home';
import '@BackendMenu';

/*
window.app = new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: { App },
    http: {
        root: process.env.BASE_PATH
    }
});
*/
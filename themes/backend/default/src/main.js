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
    require('@/components/input/Checkbox'),
    require('@/components/Modal'),
    require('@/components/ErrorModal'),
]);

// Load plugins
import './plugins.js';
import './store/error.js';

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
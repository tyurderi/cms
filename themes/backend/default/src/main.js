import { registerComponents } from './util';

import Vue from 'vue'
import App from './App'

import router from './router.js'
import store from './store';

import Http from 'vue-resource';
import Events from 'vue-events';
import VueProgressBar from 'vue-progressbar';
import CustomProgressBar from '@/components/progressbar';

Vue.use(Http);
Vue.use(Events);
Vue.use(VueProgressBar, {
    color: 'rgba(52, 152, 219,1.0)',
    failedColor: 'red',
    height: '2px'
});

Vue.use(CustomProgressBar);

registerComponents([
    require('@/components/input/Checkbox'),
    require('@/components/Modal'),
    require('@/components/ErrorModal'),
    require('@/components/Note'),
]);

// Load plugins
import './plugins.js';
import './store/error.js';

window.app = new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: { App }
});

Vue.http.options.root = process.env.BASE_PATH;
import { registerComponents } from './util';

import Vue from 'vue'
import App from './App'

import router from './routes'
import store from './store';

// register custom stores here
import '@BackendMenu/store/menu';

import Http from 'vue-resource';
import Events from 'vue-events';

Vue.use(Http);
Vue.use(Events);

registerComponents([
    require('@/components/input/Checkbox')
]);

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

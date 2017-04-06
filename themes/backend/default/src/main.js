import Vue from 'vue'
import App from './App'

import router from './router'
import store from './store';

import Http from 'vue-resource';
import Events from 'vue-events';

Vue.use(Http);
Vue.use(Events);

import Checkbox from '@/components/input/Checkbox';

Vue.component(Checkbox.name, Checkbox);

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
import { registerComponents } from './util';

import Vue from 'vue'
import App from './App'

import router from './router.js'
import store from './store';

import Http from 'vue-resource';
import Events from 'vue-events';
import VueProgressBar from 'vue-progressbar';
import VueTweening from 'vue-tweening'
import VueTooltip from 'v-tooltip';

import CustomProgressBar from '@/plugins/progressbar';
import VuePermission from '@/plugins/permission';
import VueToast from '@/plugins/toast';

Vue.use(Http);
Vue.use(Events);
Vue.use(VueProgressBar, {
    color: 'rgba(52, 152, 219,1.0)',
    failedColor: 'red',
    height: '2px'
});

Vue.use(CustomProgressBar);
Vue.use(VueTweening);
Vue.use(VueTooltip);
Vue.use(VuePermission);
Vue.use(VueToast);

registerComponents([
    require('@/components/Input/Checkbox'),
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
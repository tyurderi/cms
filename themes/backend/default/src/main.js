import Vue from 'vue'
import App from './App'
import router from './router'
import Http from 'vue-resource';

Vue.use(Http);

new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App },
    http: {
        root: process.env.BASE_PATH
    }
});
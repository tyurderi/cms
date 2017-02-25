import Vue from 'vue'
import App from './App'
import router from './router'
import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;

new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App }
});

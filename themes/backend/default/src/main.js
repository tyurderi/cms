import Vue from 'vue'
import App from './App'
import router from './router'
import jQuery from 'jquery';
import path from 'path';

window.$ = window.jQuery = jQuery;
window.$.url = (url) => {
  return path.join(process.env.BASE_PATH, url);
};

new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App }
});
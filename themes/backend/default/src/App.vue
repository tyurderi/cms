<template>
    <div id="app">
        <vue-progress-bar></vue-progress-bar>

        <transition name="fade">
            <component :is="mainView"></component>
        </transition>
        
        <error-modal></error-modal>
    </div>
</template>

<script>
import '@/assets/less/all.less';
import 'font-awesome/css/font-awesome.min.css';

import MainLoader from '@/components/MainLoader';
import Login from '@/components/Login';
import Index from '@/components/Index';

export default {
    name: 'app',
    data: () => ({
        mainView: 'mainLoader'
    }),
    components: {
        MainLoader,
        Login,
        Index
    },
    mounted()
    {
        let me = this;
        
        me.$events.on('updateView', me.updateView.bind(me));
    },
    methods: {
        updateView(viewName)
        {
            this.mainView = viewName;
        }
    }
}
</script>
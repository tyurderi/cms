<template>
    <div id="app">
        <transition name="fade">
            <component :is="mainView" v-on:updateView="updateView"></component>
        </transition>
    </div>
</template>

<script>
import '@/assets/less/all.less';

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
    methods: {
        updateView(viewName)
        {
            this.mainView = viewName;
        }
    }
}
</script>
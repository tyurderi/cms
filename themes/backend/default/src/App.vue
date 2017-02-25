<template>
    <div id="app">
        <transition name="fade">
            <component :is="mainView" v-on:updateView="updateView"></component>
        </transition>
    </div>
</template>

<script>
import '@/assets/less/all.less';

import Index from '@/components/Index';
import Login from '@/components/Login';

export default {
    name: 'app',
    data() {
        return {
            mainView: 'index'
        }
    },
    components: {
        Index,
        Login
    },
    methods: {
        updateView(viewName)
        {
            this.mainView = viewName;
        }
    }
}
</script>
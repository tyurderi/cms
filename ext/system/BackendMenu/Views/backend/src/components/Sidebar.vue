<template>
    <div class="sidebar" :class="{ closed: !menuOpen }" ref="sidebar">
        <div class="sidebar-header">
            <div class="header" v-if="menuOpen">
                vuex cms
            </div>
            <div class="menu-toggle" @click="menuOpen=!menuOpen">
                <i class="fa fa-arrow-left" v-if="menuOpen"></i>
                <i class="fa fa-bars" v-else></i>
            </div>
        </div>
        
        <div ref="menu">
            <sidebar-menu ref="menu"></sidebar-menu>
        </div>
    </div>
</template>

<script>
import velocity from 'velocity-animate';
import '../assets/less/_components/sidebar.less';
import SidebarMenu from './Menu.vue';

export default {
    name: 'sidebar',
    components: {
        SidebarMenu
    },
    data: () => ({
        menuOpen: true
    }),
    watch: {
        menuOpen(open)
        {
            velocity(this.$refs.sidebar, { width: open ? 230 : 40 }, { duration: 250 });
            velocity(this.$refs.menu, { opacity: open ? 1 : 0 }, { duration: 250 });
        }
    }
}
</script>

<style lang="less" scoped>
.sidebar-header {
    background: darken(#c0392b, 5);
    height: 40px;
    display: flex;
    flex-direction: row;
    .header {
        flex: 1;
        line-height: 40px;
        padding: 0 10px;
        word-break: keep-all;
        white-space: nowrap;
    }
    .menu-toggle {
        width: 40px;
        height: 40px;
        color: #fff;
        text-align: center;
        line-height: 40px;
        cursor: pointer;
        &:hover {
            background: darken(#c0392b, 7.5)
        }
    }
}
</style>
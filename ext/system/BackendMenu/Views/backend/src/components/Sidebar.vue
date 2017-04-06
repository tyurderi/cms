<template>
    <div class="sidebar">
        <sidebar-menu></sidebar-menu>
    </div>
</template>

<script>
import '../assets/less/_components/sidebar.less';
import SidebarMenu from './Menu.vue';

export default {
    name: 'sidebar',
    components: {
        SidebarMenu
    }
}
</script>
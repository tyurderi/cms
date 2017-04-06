<template>
    <div class="sidebar">
        <sidebar-menu></sidebar-menu>
    </div>
</template>

<script>
import SidebarMenu from './Menu.vue';

export default {
    name: 'sidebar',
    components: {
        SidebarMenu
    }
}
</script>
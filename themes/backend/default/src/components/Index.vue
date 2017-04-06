<template>
    <div class="main-container">
        <sidebar></sidebar>
        
        <router-view></router-view>
    </div>
</template>

<script>
import Sidebar from '@BackendMenu/components/Sidebar';

export default {
    name: 'index',
    components: { Sidebar }
}
</script>
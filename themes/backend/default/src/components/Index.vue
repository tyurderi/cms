<template>
    <div class="main-container">
        <sidebar></sidebar>
        
        <div class="main">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import Sidebar from '@BackendMenu/components/Sidebar';

export default {
    name: 'index',
    components: { Sidebar }
}
</script>
<template>
    <div class="sidebar" :style="{ width: sidebarWidth + 'px' }">
        <div class="sidebar-header">
            <div class="header" :style="{ width: headerWidth + 'px', opacity: opacity }">
                <span>vuex cms</span>
            </div>
            <div class="menu-toggle" @click="toggleMenu">
                <i class="fa fa-arrow-left" v-if="menuOpen"></i>
                <i class="fa fa-bars" v-else></i>
            </div>
        </div>
        
        <div>
            <sidebar-menu ref="menu"></sidebar-menu>
        </div>
    </div>
</template>

<script>
import '../assets/less/_components/sidebar.less';
import SidebarMenu from './Menu.vue';

export default {
    name: 'sidebar',
    components: {
        SidebarMenu
    },
    data: () => ({
        menuOpen: true,
        sidebarWidth: 230,
        opacity: 1,
        headerWidth: 190
    }),
    methods: {
        toggleMenu()
        {
            let me = this;

            if (this.menuOpen)
            {
                this.$tweening({
                    start: { sidebarWidth: 230, opacity: 1, headerWidth: 190 },
                    end:   { sidebarWidth: 40,  opacity: 0, headerWidth: 0 },
                    within: 125,
                    via: this.$tweening.Easing.Linear.None,
                    tween(v)
                    {
                        me.sidebarWidth = v.sidebarWidth;
                        me.opacity      = v.opacity;
                        me.headerWidth  = v.headerWidth;
                    }
                });
                
                this.menuOpen = false;
            }
            else
            {
                this.$tweening({
                    start: { sidebarWidth: 40,  opacity: 0, headerWidth: 0 },
                    end:   { sidebarWidth: 230, opacity: 1, headerWidth: 190 },
                    within: 125,
                    via: this.$tweening.Easing.Linear.None,
                    tween(v)
                    {
                        me.sidebarWidth = v.sidebarWidth;
                        me.opacity      = v.opacity;
                        me.headerWidth  = v.headerWidth;
                    }
                });

                this.menuOpen = true;
            }
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
        word-break: keep-all;
        white-space: nowrap;
        overflow: hidden;
        span {
            display: block;
            padding: 0 10px;
        }
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
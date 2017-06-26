<template>
    <div class="sidebar">
        <div class="sidebar-header">
           Vuex CMS
        </div>
        <div class="menu">
            <sidebar-menu ref="menu"></sidebar-menu>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
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
    mounted()
    {
        let me = this;
        
        me.menuOpen   = localStorage.getItem('cms.backend_menu.menu_open') === 'true';
        me.tweenState = {
            visible: {
                sidebarWidth: 230,
                opacity: 1,
                headerWidth: 190
            },
            hidden: {
                sidebarWidth: 40,
                opacity: 0,
                headerWidth: 0
            }
        };
        
        me.updateTween(me.tweenState[me.menuOpen ? 'visible' : 'hidden']);
    },
    watch: {
        menuOpen(value)
        {
            localStorage.setItem('cms.backend_menu.menu_open', value);
        }
    },
    methods: {
        toggleMenu()
        {
            let me = this;

            if (this.menuOpen)
            {
                this.$tweening({
                    start: _.clone(me.tweenState['visible']),
                    end:   me.tweenState['hidden'],
                    within: 125,
                    via: this.$tweening.Easing.Linear.None,
                    tween: me.updateTween
                });
                
                this.menuOpen = false;
            }
            else
            {
                this.$tweening({
                    start: _.clone(me.tweenState['hidden']),
                    end:   me.tweenState['visible'],
                    within: 125,
                    via: this.$tweening.Easing.Linear.None,
                    tween: me.updateTween
                });

                this.menuOpen = true;
            }
        },
        updateTween(v)
        {
            let me = this;

            me.sidebarWidth = v.sidebarWidth;
            me.opacity      = v.opacity;
            me.headerWidth  = v.headerWidth;
        }
    }
}
</script>
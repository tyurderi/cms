<template>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="site-list">
                <div class="site" v-for="(site, key) in sites" :key="key"
                     v-if="site.id == selectedSite || selectingSite"
                     @click="selectSite(site)">
                    <div class="site-title">
                        {{site.label}}
                    </div>
                    <div class="site-host">
                        {{site.host}}
                    </div>
                </div>
            </div>
        </div>
        <div class="menu" v-if="!selectingSite">
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
    data: () => ({
        selectedSite: 1,
        selectingSite: false
    }),
    computed: {
        sites()
        {
            return this.$store.getters['site/items']
        }
    },
    methods: {
        selectSite(site)
        {
            if (this.selectingSite === true)
            {
                this.selectedSite  = site.id;
                this.selectingSite = false;
            }
            else
            {
                this.selectingSite = true;
            }
        }
    },
    components: {
        SidebarMenu
    }
}
</script>
<template>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="site" v-if="site" @click="selectingSite = true">
                <div class="site-title">
                    {{site.label}}
                </div>
                <div class="site-host">
                    {{site.host}}
                </div>
            </div>
        </div>
        <div class="menu">
            <sidebar-menu ref="menu"></sidebar-menu>
        </div>
        <modal mode="fixed" width="500px" height="300px" v-if="selectingSite">
            <div class="site-selector" slot="content">
                <div class="selector-header">
                    <div class="header-title">
                         Select site
                    </div>
                    <div class="header-close" @click="selectingSite = false">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
                <div class="selector-sites">
                    <div class="site" v-for="(site, key) in sites"
                         :class="{ active: parseInt(site.id) === selectedSite }"
                         @click="selectedSite = parseInt(site.id)">
                        <div class="site-title">
                            {{site.label}}
                        </div>
                        <div class="site-host">
                            {{site.host}}
                        </div>
                    </div>
                </div>
            </div>
        </modal>
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
        },
        site()
        {
            return this.sites.find(site => parseInt(site.id) === this.selectedSite);
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

<style lang="less">
.site-selector {
    height: 100%;
    display: flex;
    flex-direction: column;
    .selector-header {
        display: flex;
        flex-direction: row;
        border-bottom: 1px solid #ddd;
        .header-title {
            flex: 1;
            padding: 15px;
            font-weight: bold;
        }
        .header-close {
            width: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            &:hover {
                background: rgba(0, 0, 0, 0.05);
                cursor: pointer;
            }
        }
    }
    .selector-sites {
        flex: 1;
        overflow-y: auto;
        .site {
            padding: 15px;
            border-bottom: 1px dashed #ddd;
            &.active {
                .site-title {
                    font-weight: bold;
                }
            }
            &:last-child {
                border-bottom: 0 none;
            }
            &:hover {
                background: rgba(0, 0, 0, 0.05);
                cursor: pointer;
            }
            .site-title {
            
            }
            .site-label {
            
            }
        }
    }
}
</style>
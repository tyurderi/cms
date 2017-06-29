<template>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="domain" v-if="domain" @click="selectingDomain = true">
                <div class="domain-label">
                    {{domain.label}}
                </div>
                <div class="domain-host">
                    {{domain.host}}
                </div>
            </div>
        </div>
        <div class="menu">
            <sidebar-menu ref="menu"></sidebar-menu>
        </div>
        <modal mode="fixed" width="500px" height="300px" v-if="selectingDomain">
            <div class="domain-selector" slot="content">
                <div class="selector-header">
                    <div class="header-title">
                         Select domain
                    </div>
                    <div class="header-close" @click="selectingDomain = false">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
                <div class="selector-items">
                    <div class="item" v-for="(domain, key) in domains"
                         :class="{ active: parseInt(domain.id) === selectedDomain }"
                         @click="selectedDomain = parseInt(domain.id)">
                        <div class="item-title">
                            {{domain.label}}
                        </div>
                        <div class="item-host">
                            {{domain.host}}
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
        selectedDomain: 1,
        selectingDomain: false
    }),
    computed: {
        domains()
        {
            return this.$store.getters['domain/items']
        },
        domain()
        {
            return this.domains.find(domain => parseInt(domain.id) === this.selectedDomain);
        }
    },
    components: {
        SidebarMenu
    }
}
</script>

<style lang="less">
.domain-selector {
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
    .selector-items {
        flex: 1;
        overflow-y: auto;
        .item {
            padding: 15px;
            border-bottom: 1px dashed #ddd;
            &.active {
                .item-title {
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
            .item-title {
            
            }
            .item-label {
            
            }
        }
    }
}
</style>
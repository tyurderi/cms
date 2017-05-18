<template>
    <div class="plugin-manager">
        <h1>Plugin Manager</h1>
        <h2>{{items.length}} plugins available</h2>
        <h4>Note that system plugins can only be changed through command line.</h4>
        
        <table class="plugin-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Namespace</th>
                <th>Name</th>
                <th>Label</th>
                <th>Version</th>
                <th>Created</th>
                <th>Changed</th>
                <th>Enabled</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, key) in items" :key="key">
                <td>{{item.id}}</td>
                <td>{{item.namespace}}</td>
                <td>{{item.name}}</td>
                <td>{{item.label}}</td>
                <td>{{item.version}}</td>
                <td>{{item.created}}</td>
                <td>{{item.changed}}</td>
                <td>{{item.active ? 'Yes' : 'No'}}</td>
                <td class="actions">
                    <ul v-if="item.namespace !== 'system'">
                        <li v-if="!item.active">
                            <a href="#" @click.prevent="install(item)"><i class="fa fa-plus"></i></a>
                        </li>
                        <li v-if="item.active">
                            <a href="#" @click.prevent="uninstall(item)"><i class="fa fa-minus"></i></a>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
        </table>
        
        <plugin-installer :plugin="installingPlugin"
                            @reject="installingPlugin = null"></plugin-installer>
        <plugin-uninstaller :plugin="uninstallingPlugin"
                            @reject="uninstallingPlugin = null"></plugin-uninstaller>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

import PluginInstaller from '@PluginManager/components/PluginInstaller';
import PluginUninstaller from '@PluginManager/components/PluginUninstaller';

export default {
    components: { PluginInstaller, PluginUninstaller },
    computed: {
        ...mapGetters({
            items: 'plugin/items'
        })
    },
    data: () => ({
        installingPlugin: null,
        uninstallingPlugin: null
    }),
    mounted()
    {
        this.$store.dispatch('plugin/load');
    },
    methods: {
        install(plugin)
        {
            this.installingPlugin = plugin;
        },
        uninstall(plugin)
        {
            this.uninstallingPlugin = plugin;
        }
    }
}
</script>

<style lang="less" scoped>
    .plugin-manager {
        padding: 10px;
        position: relative;
        height: 100%;
    }
    table.plugin-list {
        background: #fff;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        td.actions {
            ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
        }
    }
</style>
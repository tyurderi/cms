<template>
    <div class="plugin-manager">
        <h1>Plugin Manager</h1>
        <h2>{{items.length}} plugins available</h2>

        <note text="Be careful when uninstalling system plugins. It can break the entire system." type="warning"></note>
        <note text="When installing backend plugins you probably need to recompile the themes."></note>
        
        <table class="plugin-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Installed</th>
                <th>Label</th>
                <th>Version</th>
                <th>Created</th>
                <th>Changed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, key) in items" :key="key">
                <td>{{item.id}}</td>
                <td>
                    <i class="fa fa-check" v-if="item.active"></i>
                    <i class="fa fa-times" v-else></i>
                </td>
                <td>
                    <small>{{item.namespace}}</small>
                    {{item.label}}
                </td>
                <td>{{item.version}}</td>
                <td>{{item.created}}</td>
                <td>{{item.changed}}</td>
                <td class="actions">
                    <ul>
                        <li v-if="!item.active">
                            <a href="#" @click.prevent="install(item)"><i class="fa fa-plus"></i></a>
                        </li>
                        <li v-if="item.active">
                            <a href="#" @click.prevent="uninstall(item)"><i class="fa fa-minus"></i></a>
                        </li>
                        <li v-if="item.active && item.needUpdate">
                            <a href="#" @click.prevent="update(item)"><i class="fa fa-refresh"></i></a>
                        </li>
                        <li v-if="!item.active">
                            <a href="#" @click.prevent="remove(item)"><i class="fa fa-trash"></i></a>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
        </table>
        
        <plugin-manager :plugin="plugin" :action="action" @done="done"></plugin-manager>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

import PluginManager from '@PluginManager/components/PluginManager';

export default {
    components: { PluginManager },
    computed: {
        ...mapGetters({
            items: 'plugin/items'
        })
    },
    data: () => ({
        plugin: null,
        action: null
    }),
    mounted()
    {
        this.$store.dispatch('plugin/load');
    },
    methods: {
        install(plugin)
        {
            this.plugin = plugin;
            this.action = 'install';
        },
        uninstall(plugin)
        {
            this.plugin = plugin;
            this.action = 'uninstall';
        },
        update(plugin)
        {
            this.plugin = plugin;
            this.action = 'update';
        },
        remove(plugin)
        {
            this.plugin = plugin;
            this.action = 'remove';
        },
        done()
        {
            this.plugin = null;
            this.action = null;
            
            this.$store.dispatch('plugin/load');
            this.$store.dispatch('menu/load');
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
                li {
                    display: inline-block;
                    margin: 0 5px 0 0;
                    a {
                        transition: all 125ms;
                        &:hover {
                            color: #c0392b;
                        }
                    }
                }
            }
        }
    }
</style>
<template>
    <div class="user">
        <div class="head">
            <div class="title">
                {{groups.length}} Groups
            </div>
            <ul class="actions">
                <li><a href="#" @click.prevent="load"><i class="fa fa-refresh"></i></a></li>
                <li><a href="#"><i class="fa fa-search"></i></a></li>
                <li><a href="#"><i class="fa fa-filter"></i></a></li>
                <li><a href="#"><i class="fa fa-plus"></i></a></li>
            </ul>
        </div>
        <div class="body">
            <table class="user-list">
                <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Name</th>
                    <th width="100">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, key) in groups" :key="key">
                    <td>{{item.id}}</td>
                    <td>{{item.label}}</td>
                    <td class="actions">
                        <ul>
                            <li>
                                <a href="#" @click.prevent="edit(item)"><i class="fa fa-edit"></i></a>
                                <a href="#"><i class="fa fa-trash"></i></a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    computed: {
        ...mapGetters({
            groups: 'group/items'
        })
    },
    mounted()
    {
        this.load();
    },
    methods: {
        load()
        {
            this.$progress.start();
            this.$store.dispatch('group/load', () => {
                this.$progress.finish();
            });
        }
    }
}
</script>

<style lang="less" scoped>
.user {
    position: relative;
    height: 100%;
}
table.user-list {
    background: #fff;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
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
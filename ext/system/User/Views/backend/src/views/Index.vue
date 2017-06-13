<template>
    <div class="user">
        <div class="head">
            <div class="title">
                {{users.length}} Users
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
                    <th width="200">Group</th>
                    <th>Email</th>
                    <th width="200">Created</th>
                    <th width="200">Changed</th>
                    <th width="100">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, key) in users" :key="key">
                    <td>{{user.id}}</td>
                    <td>{{getGroupName(user.groupID)}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.created}}</td>
                    <td>{{user.changed}}</td>
                    <td class="actions">
                        <ul>
                            <li>
                                <a href="#" @click.prevent="edit(user)"><i class="fa fa-edit"></i></a>
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
import async from 'async';

export default {
    computed: {
        ...mapGetters({
            users: 'user/items',
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
            
            async.series([
                done => this.$store.dispatch('user/load', done),
                done => this.$store.dispatch('group/load', done)
            ], (response, error) => {
                this.$progress.finish();
            });
        },
        edit(user)
        {
            this.$router.push({ name: 'user-edit', params: { id: user.id } });
        },
        getGroupName(groupID)
        {
            let group = this.groups.find(group => group.id === groupID);
            
            return group ? group.label : 'Unknown';
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
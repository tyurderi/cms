<template>
    <div class="user">
        <h1>{{users.length}} Users</h1>
        
        <table class="user-list">
            <thead>
            <tr>
                <th>ID</th>
                <th>Group</th>
                <th>Email</th>
                <th>Created</th>
                <th>Changed</th>
                <th>Actions</th>
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
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    computed: {
        ...mapGetters({
            users: 'user/items',
            groups: 'user/groups'
        })
    },
    mounted()
    {
        this.$store.dispatch('user/load');
    },
    methods: {
        edit(user)
        {
            this.$store.commit('user/setUser', user);
            this.$router.push('/users/edit');
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
    padding: 10px;
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
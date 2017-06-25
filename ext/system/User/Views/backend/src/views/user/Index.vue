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
                <li><a href="#" @click.prevent="create"><i class="fa fa-plus"></i></a></li>
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
                                <a href="#" @click.prevent="remove(user)"><i class="fa fa-trash"></i></a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <v-question-modal v-if="removeProgress.affectedUser && removeProgress.isAsking === true"
                          label="Delete User" text="Are you sure?"
                          @accept="doRemove" @reject="removeProgress.affectedUser = null"></v-question-modal>
        <v-progress-modal v-if="removeProgress.affectedUser && removeProgress.isProgressing === true"
                          label="Deleting User" text="And its gone"></v-progress-modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import async from 'async';

import VQuestionModal from '@/components/Modal/Question';
import VProgressModal from '@/components/Modal/Progress';

export default {
    components: {
        VQuestionModal,
        VProgressModal
    },
    computed: {
        ...mapGetters({
            groups: 'group/items'
        }),
        users() {
            return this.$store.getters['user/items'].filter(user => user.id !== 'new');
        }
    },
    mounted()
    {
        this.load();
    },
    data: () => ({
        removeProgress: {
            affectedUser: null,
            isAsking: false,
            isProgressing: false
        }
    }),
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
        create()
        {
            this.$router.push({ name: 'user-edit', params: { id: 'new' } });
        },
        edit(user)
        {
            this.$router.push({ name: 'user-edit', params: { id: user.id } });
        },
        remove(user)
        {
            this.removeProgress.affectedUser = user;
            this.removeProgress.isAsking     = true;
        },
        doRemove()
        {
            this.removeProgress.isAsking      = false;
            this.removeProgress.isProgressing = true;

            this.$http.post('api/user/remove', { id: this.removeProgress.affectedUser.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        this.removeProgress.affectedUser  = null;
                        this.removeProgress.isAsking      = false;
                        this.removeProgress.isProgressing = false;

                        // refresh data
                        this.load();
                    }
                });
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
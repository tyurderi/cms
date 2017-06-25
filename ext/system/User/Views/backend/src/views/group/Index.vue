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
                <li><a href="#" @click.prevent="create"><i class="fa fa-plus"></i></a></li>
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
                                <a href="#" @click.prevent="remove(item)"><i class="fa fa-trash"></i></a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <v-question-modal v-if="removeProgress.affectedGroup && removeProgress.isAsking === true"
                          label="Delete Group" text="Are you sure?"
                          @accept="doRemove" @reject="removeProgress.affectedGroup = null"></v-question-modal>
        <v-progress-modal v-if="removeProgress.affectedGroup && removeProgress.isProgressing === true"
                          label="Deleting Group" text="And its gone"></v-progress-modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import VQuestionModal from '@/components/Modal/Question';
import VProgressModal from '@/components/Modal/Progress';

export default {
    components: {
        VQuestionModal,
        VProgressModal
    },
    data: () => ({
        removeProgress: {
            affectedGroup: null,
            isAsking: false,
            isProgressing: false
        }
    }),
    computed: {
        groups()
        {
            return this.$store.getters['group/items'].filter(group => group.id !== 'new')
        }
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
        },
        create()
        {
            this.$router.push({ name: 'users-groups-edit', params: { id: 'new' } });
        },
        edit(item)
        {
            this.$router.push({ name: 'users-groups-edit', params: { id: item.id } });
        },
        remove(group)
        {
            this.removeProgress.affectedGroup = group;
            this.removeProgress.isAsking      = true;
        },
        doRemove()
        {
            this.removeProgress.isAsking      = false;
            this.removeProgress.isProgressing = true;

            this.$http.post('api/group/remove', { id: this.removeProgress.affectedGroup.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        this.removeProgress.affectedGroup  = null;
                        this.removeProgress.isAsking      = false;
                        this.removeProgress.isProgressing = false;

                        // refresh data
                        this.load();
                    }
                });
        },
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
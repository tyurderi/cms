<template>
    <div class="user">
        <v-grid :settings="gridSettings" :data="users"
                @create="create" @edit="edit" @remove="remove" @load="load"></v-grid>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import async from 'async';

import VGrid from '@/components/Grid';

export default {
    name: 'user',
    data: () => ({
        gridSettings: {
            autoLoad: true,
            headTitle: 'Users',
            columns: {
                id:      { label: 'ID', width: 80 },
                group:   { label: 'Group', width: 200 },
                email:   { label: 'Email' },
                created: { label: 'Created', width: 200 },
                changed: { label: 'Changed', width: 200 }
            },
            actions: [
                {
                    name: 'edit',
                    iconCls: 'fa fa-edit'
                },
                {
                    name: 'remove',
                    iconCls: 'fa fa-trash',
                    ask: {
                        questionLabel: 'Delete User',
                        questionText: 'Are you sure?',
                        progressLabel: 'Deleting User',
                        progressText: ''
                    }
                }
            ],
            renderer: {
                group: (grid, item) =>
                {
                    let group = grid.$store.getters['group/items'].find(group => group.id === item.groupID);

                    if (group)
                    {
                        return group.label;
                    }

                    return 'Unknown';
                }
            },
            searchMethod(item, term)
            {
                return item.email.toLowerCase().indexOf(term) !== -1
                    || item.firstname.toLowerCase().indexOf(term) !== -1
                    || item.lastname.toLowerCase().indexOf(term) !== -1;
            },
            permissions: {
                create: 'user.create',
                edit:   'user.edit',
                remove: 'user.remove'
            }
        }
    }),
    components: {
        VGrid
    },
    computed: {
        users()
        {
            return this.$store.getters['user/items'];
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
            
            async.series([
                done => this.$store.dispatch('user/load', done),
                done => this.$store.dispatch('group/load', done)
            ], (response, error) => {
                this.$progress.finish();
            });
        },
        create()
        {
            this.$router.push({ name: 'user-create' });
        },
        edit(user)
        {
            this.$router.push({ name: 'user-edit', params: { id: user.id } });
        },
        remove(user, done)
        {
            this.$http.post('api/user/remove', { id: user.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        done();

                        // refresh data
                        this.load();
                        
                        // show toast message
                        this.$toast.push({
                            text: 'The user were deleted successfully',
                            type: 'success',
                            delay: 3000
                        });
                    }
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
</style>
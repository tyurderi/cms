<template>
    <div class="group">
        <v-grid :settings="gridSettings" :data="groups"
                @create="create" @edit="edit" @remove="remove" @load="load"></v-grid>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import VGrid from '@/components/Grid';

export default {
    components: {
        VGrid
    },
    data: () => ({
        gridSettings: {
            autoLoad: true,
            headTitle: 'Groups',
            columns: {
                id: { label: 'ID', width: 80 },
                label: { label: 'Label' }
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
                        questionLabel: 'Delete Group',
                        questionText: 'Are you sure?',
                        progressLabel: 'Deleting Group',
                        progressText: ''
                    }
                }
            ],
            searchMethod(item, term)
            {
                return item.label.toLowerCase().indexOf(term) !== -1;
            },
            permissions: {
                create: 'user.group.create',
                edit:   'user.group.edit',
                remove: 'user.group.remove'
            }
        }
    }),
    computed: {
        groups()
        {
            return this.$store.getters['group/items']
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
            this.$router.push({ name: 'user-group-create' });
        },
        edit(item)
        {
            this.$router.push({ name: 'user-group-edit', params: { id: item.id } });
        },
        remove(group, done)
        {
            this.$http.post('api/group/remove', { id: group.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        done();

                        // refresh data
                        this.load();

                        // show toast message
                        this.$toast.push({
                            text: 'The group were deleted successfully',
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
.group {
    position: relative;
    height: 100%;
}
</style>
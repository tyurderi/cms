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
            data: [],
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
                        progressText: 'And its gone'
                    }
                }
            ]
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
<template>
    <div class="config-sites">
        <v-grid :settings="gridSettings" :data="sites"
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
            headTitle: 'Sites',
            columns: {
                id:      { label: 'ID', width: 80 },
                label:   { label: 'Label' },
                host:    { label: 'Host' },
                created: { label: 'Created' },
                changed: { label: 'Changed' }
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
                        questionLabel: 'Delete Site',
                        questionText: 'Are you sure?',
                        progressLabel: 'Deleting Site',
                        progressText: ''
                    }
                }
            ],
            permissions: {
                create: '',
                edit:   '',
                remove: ''
            }
        }
    }),
    components: {
        VGrid
    },
    computed: {
        sites()
        {
            return this.$store.getters['site/items'];
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
                done => this.$store.dispatch('site/load', done),
            ], (response, error) => {
                this.$progress.finish();
            });
        },
        create()
        {
            this.$router.push({ name: 'config-site-create' });
        },
        edit(site)
        {
            this.$router.push({ name: 'config-site-edit', params: { id: site.id } });
        },
        remove(site, done)
        {
            this.$http.post('api/site/remove', { id: site.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        done();

                        // refresh data
                        this.load();

                        // show toast message
                        this.$toast.push({
                            text: 'The site were deleted successfully',
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
.config-sites {
    position: relative;
    height: 100%;
}
</style>
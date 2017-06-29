<template>
    <div class="config-domains">
        <v-grid :settings="gridSettings" :data="domains"
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
            headTitle: 'Domains',
            columns: {
                id:      { label: 'ID', width: 80 },
                active:  { label: 'Active' },
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
                        questionLabel: 'Delete Domain',
                        questionText: 'Are you sure?',
                        progressLabel: 'Deleting Domain',
                        progressText: ''
                    }
                }
            ],
            renderer: {
                active(grid, item)
                {
                    return parseInt(item.active) === 1
                        ? '<i class="fa fa-check"></i>'
                        : '<i class="fa fa-times"></i>';
                }
            },
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
        domains()
        {
            return this.$store.getters['domain/items'];
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
                done => this.$store.dispatch('domain/load', done),
            ], (response, error) => {
                this.$progress.finish();
            });
        },
        create()
        {
            this.$router.push({ name: 'config-domain-create' });
        },
        edit(site)
        {
            this.$router.push({ name: 'config-domain-edit', params: { id: site.id } });
        },
        remove(site, done)
        {
            this.$http.post('api/domain/remove', { id: site.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        done();

                        // refresh data
                        this.load();

                        // show toast message
                        this.$toast.push({
                            text: 'The domain were deleted successfully',
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
.config-domains {
    position: relative;
    height: 100%;
}
</style>
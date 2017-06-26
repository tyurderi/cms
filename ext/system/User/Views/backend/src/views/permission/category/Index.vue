<template>
    <div class="permission-category">
        <v-grid :settings="gridSettings" :data="categories"
                @create="create" @edit="edit" @remove="remove" @load="load"></v-grid>
    </div>
</template>

<script>
import async from 'async';
import VGrid from '@/components/Grid';

export default {
    name: 'permission-category',
    data: () => ({
        gridSettings: {
            autoLoad: true,
            headTitle: 'Permission Categories',
            columns: {
                id: { label: 'ID', width: 80 },
                label: { label: 'Label', width: 200 },
                description: { label: 'Description' }
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
                        questionLabel: 'Delete Category',
                        questionText: 'Are you sure?',
                        progressLabel: 'Deleting Category',
                        progressText: ''
                    }
                }
            ],
            searchMethod(item, term)
            {
                return item.label.toLowerCase().indexOf(term) !== -1
                    || item.description.toLowerCase().indexOf(term) !== -1;
            }
        }
    }),
    computed: {
        categories()
        {
            return this.$store.getters['permission/category/items'];
        }
    },
    methods: {
        load()
        {
            this.$progress.start();

            async.series([
                done => this.$store.dispatch('permission/category/load', done)
            ], (response, error) => {
                this.$progress.finish();
            })
        },
        create()
        {
            this.$router.push({ name: 'user-permission-category-create' });
        },
        edit(category)
        {
            this.$router.push({ name: 'user-permission-category-edit', params: { id: category.id } });
        },
        remove(category, done)
        {
            this.$http.post('api/permission/removeCategory', { id: category.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        done();

                        // refresh data
                        this.load();
                    }
                }, (response) => {
                    done();
                    this.$store.dispatch('error/push', response);
                });
        }
    },
    components: {
        VGrid
    }
}
</script>
<template>
    <div class="permission">
        <v-grid :settings="gridSettings" :data="permissions"
                @create="create" @edit="edit" @remove="remove" @load="load"></v-grid>
    </div>
</template>

<script>
import async from 'async';
import VGrid from '@/components/Grid';

export default {
    name: 'permission',
    data: () => ({
        gridSettings: {
            autoLoad: true,
            headTitle: 'Permissions',
            columns: {
                id:          { label: 'ID',       width: 80 },
                category:    { label: 'Category', width: 200 },
                name:        { label: 'Name',     width: 400 },
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
                        questionLabel: 'Delete Permission',
                        questionText: 'Are you sure?',
                        progressLabel: 'Deleting Permission',
                        progressText: ''
                    }
                }
            ],
            renderer: {
                category(grid, item)
                {
                    let categories = grid.$store.getters['permission/category/items'],
                        category   = categories.find(category => category.id === item.categoryID);

                    if (category)
                    {
                        return category.label;
                    }
                    
                    return 'Unknown';
                }
            },
            searchMethod(item, term)
            {
                return item.name.toLowerCase().indexOf(term) !== -1
                    || item.description.toLowerCase().indexOf(term) !== -1;
            }
        }
    }),
    computed: {
        permissions()
        {
            return this.$store.getters['permission/items'];
        }
    },
    methods: {
        load()
        {
            this.$progress.start();
            
            async.series([
                done => this.$store.dispatch('permission/load', done),
                done => this.$store.dispatch('permission/category/load', done)
            ], (response, error) => {
                this.$progress.finish();
            })
        },
        create()
        {
            this.$router.push({ name: 'user-permission-create' });
        },
        edit(permission)
        {
            this.$router.push({ name: 'user-permission-edit', params: { id: permission.id } });
        },
        remove(permission, done)
        {
            this.$http.post('api/permission/remove', { id: permission.id })
                .then((response) => {
                    if (response.body.success === true)
                    {
                        // reset remove progress data
                        done();

                        // refresh data
                        this.load();

                        // show toast message
                        this.$toast.push({
                            text: 'The permission were deleted successfully',
                            type: 'success',
                            delay: 3000
                        });
                    }
                });
        }
    },
    components: {
        VGrid
    }
}
</script>
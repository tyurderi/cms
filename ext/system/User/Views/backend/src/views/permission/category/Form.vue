<template>
    <div class="permission-category-form">
        <div class="head">
            <div class="title">
                <span v-if="isNew">
                    Create permission category
                </span>
                <span v-else>
                    Edit permission category
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="permission_category"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="category">
                <form @submit.prevent="submit" id="permission_category">
                    <div class="form-item" v-if="category.id !== null">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="category.id" readonly />
                    </div>
                    <div class="form-item">
                        <label for="label">
                            Label
                        </label>
                        <input type="text" id="label" v-model="category.label" />
                    </div>
                    <div class="form-item">
                        <label for="description">
                            Description
                        </label>
                        <textarea id="description" v-model="category.description"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import async from 'async';

export default {
    name: 'user-permission-category-form',
    data: () => ({
        editingCategory: {
            id: null,
            label: '',
            description: ''
        }
    }),
    computed: {
        category()
        {
            if (this.isNew)
            {
                return this.editingCategory;
            }

            return this.$store.getters['permission/category/items']
                .find(category => category.id === this.$route.params.id)
        },
        isNew()
        {
            return this.$route.name === 'user-permission-category-create';
        }
    },
    mounted()
    {
        let categoryID = this.$route.params.id;

        this.$progress.start();

        async.series([
            (done) =>
            {
                if (this.isNew)
                {
                    done();
                    return;
                }

                // Do not load permission when it's already assigned
                if (this.category && this.category.id && categoryID === this.category.id)
                {
                    done();
                    return;
                }

                this.$http.post('api/permission/getCategory', { id: categoryID })
                    .then(
                        response => {
                            if (!response.body.success)
                            {
                                done(true);
                                return;
                            }

                            this.$store.commit('permission/category/add', [response.body.data]);
                            done();
                        },
                        response => {
                            this.$store.dispatch('error/push', response);
                            done(true);
                        }
                    );
            }
        ], (error, results) => {
            if (error)
            {
                this.$progress.fail();
            }
            else
            {
                this.$progress.finish();
            }
        });
    },
    methods: {
        submit()
        {
            this.$progress.start();

            this.$http.post('api/permission/saveCategory', this.category)
                .then(
                    response =>
                    {
                        if (!response.body || !response.body.success)
                        {
                            this.$store.dispatch('error/push', response);
                            this.$progress.fail();
                        }
                        else
                        {
                            this.$router.push({
                                name: 'user-permission-category-edit',
                                params: { id: response.body.id }
                            });
                            this.$progress.finish();
                        }
                    },
                    response =>
                    {
                        this.$progress.fail();
                        this.$store.dispatch('error/push', response);
                    }
                )
        }
    }
}
</script>

<style lang="less" scoped>
.permission-category-form {
    position: relative;
    height: 100%;
    .form-container {
        padding: 10px;
        background: #fff;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        .form-item {
            margin: 0 0 10px 0;
            display: flex;
            flex-direction: row;
            &:last-child {
                margin: 0;
            }
            label {
                width: 100px;
                display: inline-block;
            }
            select, textarea, input {
                flex: 1;
            }
        }
    }
}
</style>
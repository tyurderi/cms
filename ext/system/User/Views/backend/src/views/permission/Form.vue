<template>
    <div class="permission-form">
        <div class="head">
            <div class="title">
                <span v-if="isNew">
                    Create permission
                </span>
                <span v-else>
                    Edit permission
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="permission"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="permission">
                <form @submit.prevent="submit" id="permission">
                    <div class="form-item" v-if="permission.id !== null">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="permission.id" readonly />
                    </div>
                    <div class="form-item">
                        <label for="name">
                            Name
                        </label>
                        <input type="text" id="name" v-model="permission.name" />
                    </div>
                    <div class="form-item">
                        <label for="label">
                            Label
                        </label>
                        <input type="text" id="label" v-model="permission.label" />
                    </div>
                    <div class="form-item">
                        <label for="description">
                            Description
                        </label>
                        <textarea id="description" v-model="permission.description"></textarea>
                    </div>
                    <div class="form-item">
                        <label for="category">
                            Category
                        </label>
                        <select id="category" v-model="permission.categoryID">
                            <option value="-1">Please select</option>
                            <template v-for="(category, key) in categories">
                                <option :value="category.id" :key="key">{{category.label}}</option>
                            </template>
                        </select>
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
    name: 'user-permission-form',
    data: () => ({
        editingPermission: {
            id: null,
            name: '',
            label: '',
            description: '',
            categoryID: -1
        }
    }),
    computed: {
        permission()
        {
            if (this.isNew)
            {
                return this.editingPermission;
            }
            
            return this.$store.getters['permission/items'].find(permission => permission.id === this.$route.params.id)
        },
        categories()
        {
            return this.$store.getters['permission/category/items'];
        },
        isNew()
        {
            return this.$route.name === 'user-permission-create';
        }
    },
    mounted()
    {
        let permissionID = this.$route.params.id;

        this.$progress.start();

        async.series([
            (done) =>
            {
                this.$store.dispatch('permission/category/load', done);
            },
            (done) =>
            {
                if (this.isNew)
                {
                    done();
                    return;
                }

                // Do not load permission when it's already assigned
                if (this.permission && this.permission.id && permissionID === this.permission.id)
                {
                    done();
                    return;
                }

                this.$http.post('api/permission/get', { id: permissionID })
                    .then(
                        response => {
                            if (!response.body.success)
                            {
                                done(true);
                                return;
                            }

                            this.$store.commit('permission/add', [response.body.data]);
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

            this.$http.post('api/permission/save', this.permission)
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
                            this.$router.push({ name: 'user-permission-edit', params: { id: response.body.id } });
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
.permission-form {
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
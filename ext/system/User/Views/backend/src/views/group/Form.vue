<template>
    <div class="group-form">
        <div class="head">
            <div class="title">
                <span v-if="isNew">
                    Create group
                </span>
                <span v-else>
                    Edit group
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="group"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="group">
                <form @submit.prevent="submit" id="group">
                    <div class="form-item" v-if="group.id !== null">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="group.id" readonly />
                    </div>
                    <div class="form-item">
                        <label for="label">
                            Label
                        </label>
                        <input type="text" id="label" v-model="group.label" />
                    </div>
                </form>
            </div>
            <div class="permission-container" v-if="group">
                <table class="list">
                    <thead>
                        <tr>
                            <th colspan="3">
                                Permissions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(category, key) in categories">
                            <tr class="permission-category">
                                <td width="200">
                                    {{category.label}}
                                </td>
                                <td>
                                    {{category.description}}
                                </td>
                            </tr>
                            <tr v-for="(permission, key) in getPermissions(category)" :key="key"
                                class="permission-item">
                                <td colspan="2">
                                    <checkbox v-model="(group.permissions||{})[permission.id]"
                                              :name="permission.name"></checkbox>
                                    <span>{{permission.description}}</span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import async from 'async';

export default {
    name: 'user-group-form',
    data: () => ({
        editingGroup: {
            id: null,
            label: '',
            permissions: {}
        }
    }),
    computed: {
        ...mapGetters({
            categories: 'permission/category/items',
            permissions: 'permission/items',
            values: 'permission/value/items'
        }),
        group()
        {
            if (this.isNew)
            {
                return this.editingGroup;
            }
            
            return this.$store.getters['group/items'].find(group => group.id === this.$route.params.id)
        },
        isNew()
        {
            return this.$route.name === 'user-group-create';
        }
    },
    mounted()
    {
        let groupID = this.$route.params.id;

        this.$progress.start();

        async.series([
            (done) => this.$store.dispatch('permission/category/load', done),
            (done) => this.$store.dispatch('permission/load', done),
            (done) =>
            {
                if (this.isNew)
                {
                    done();
                    return;
                }

                // Do not load user when it's already assigned
                if (this.group && this.group.id && groupID === this.group.id)
                {
                    done();
                    return;
                }

                this.$http.post('api/group/get', { id: groupID })
                    .then(
                        response => {
                            if (!response.body.success)
                            {
                                done(true);
                                return;
                            }

                            this.$store.commit('group/add', [response.body.data]);
                            done();
                        },
                        response => {
                            this.$store.dispatch('error/push', response);
                            done(true);
                        }
                    );
            },
            (done) =>
            {
                this.$store.dispatch('permission/value/load', { groupID, done });
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
            
            this.group.permissions = {};
            this.values.forEach(value => {
                this.group.permissions[value.permissionID] = value.value === '1';
            });
            
            this.$forceUpdate();
        });
    },
    methods: {
        submit()
        {
            this.$progress.start();

            this.$http.post('api/group/save', this.group)
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
                            this.$router.push({ name: 'user-group-edit', params: { id: response.body.id } });
                            this.$progress.finish();
                        }
                    },
                    response =>
                    {
                        this.$progress.fail();
                        this.$store.dispatch('error/push', response);
                    }
                )
        },
        getPermissions(category)
        {
            return this.permissions.filter(permission => permission.categoryID === category.id);
        }
    }
}
</script>

<style lang="less" scoped>
.group-form {
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
    .permission-container {
        background: #fff;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        margin: 10px 0 0 0;
        span.custom-checkbox {
            vertical-align: middle;
        }
        tr.permission-category td {
            background: rgba(0, 0, 0, 0.025);
            padding: 5px;
        }
        tr.permission-item td {
            padding: 5px;
        }
    }
}
</style>
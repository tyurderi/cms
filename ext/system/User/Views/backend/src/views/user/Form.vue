<template>
    <div class="user-form">
        <div class="head">
            <div class="title">
                <span v-if="isNew">
                    Create user
                </span>
                <span v-else>
                    Edit user
                </span>
            </div>
            <ul class="actions">
                <li>
                    <a href="#">
                        <button class="fa fa-check" form="user"></button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="form-container" v-if="user">
                <form @submit.prevent="submit" id="user">
                    <div class="form-item" v-if="user.id !== null">
                        <label for="id">
                            ID
                        </label>
                        <input type="text" id="id" :value="user.id" readonly />
                    </div>
                    <div class="form-item">
                        <label for="email">
                            Email
                        </label>
                        <input type="email" id="email" v-model="user.email" />
                    </div>
                    <div class="form-item">
                        <label for="password">
                            Password
                        </label>
                        <input type="password" id="password" v-model="user.password" />
                    </div>
                    <div class="form-item">
                        <label for="firstname">
                            Firstname
                        </label>
                        <input type="text" id="firstname" v-model="user.firstname" />
                    </div>
                    <div class="form-item">
                        <label for="lastname">
                            Lastname
                        </label>
                        <input type="text" id="lastname" v-model="user.lastname" />
                    </div>
                    <div class="form-item">
                        <label for="group">
                            Group
                        </label>
                        <select id="group" v-model="user.groupID">
                            <option value="-1">Please select</option>
                            <template v-for="(group, key) in groups">
                                <option :value="group.id" :key="key">{{group.label}}</option>
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
    data: () => ({
        /**
         * This will only be used when the form is in isNew mode
         */
        editingUser: {
            id: null,
            email: '',
            password: '',
            firstname: '',
            lastname: '',
            groupID: -1
        }
    }),
    computed: {
        user()
        {
            if (this.isNew)
            {
                return this.editingUser;
            }
            
            return this.$store.getters['user/items'].find(user => user.id === this.$route.params.id);
        },
        groups()
        {
            return this.$store.getters['group/items'];
        },
        isNew()
        {
            return this.$route.name === 'user-create';
        }
    },
    mounted()
    {
        let userID = this.$route.params.id;
        
        this.$progress.start();
        
        async.series([
            (done) =>
            {
                // Do not load groups when they're already loaded
                if (this.groups.length > 0)
                {
                    done();
                    return;
                }
                
                this.$http.get('api/group/list')
                    .then(
                        response => {
                            this.$store.commit('group/set', response.body.data);
                            done();
                        },
                        response => {
                            this.$store.dispatch('error/push', response);
                            done(true);
                        }
                    )
            },
            (done) =>
            {
                // Do not load anything in isNew mode
                if (this.isNew === true)
                {
                    done();
                    return;
                }
                
                // Do not load user when it's already assigned
                if (this.user && this.user.id && userID === this.user.id)
                {
                    done();
                    return;
                }
                
                this.$http.get('api/user/get', { params: { id: userID } })
                    .then(
                        response => {
                            if (!response.body.success)
                            {
                                done(true);
                                return;
                            }
                            
                            this.$store.commit('user/add', [response.body.data]);
                            this.user.password = '';
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
            
            this.$http.post('api/user/save', this.user)
                .then(
                    response => {
                        if (!response.body || !response.body.success)
                        {
                            this.$store.dispatch('error/push', response);
                            this.$progress.fail();
                        }
                        else
                        {
                            this.$router.push({ name: 'user-edit', params: { id: response.body.id } });
                            this.$progress.finish();
                            
                            this.$toast.push({
                                text: 'The user were saved successfully',
                                type: 'success',
                                delay: 3000
                            })
                        }
                    },
                    response => {
                        this.$progress.fail();
                        this.$store.dispatch('error/push', response);
                    }
                )
        }
    }
}
</script>

<style lang="less" scoped>
.user-form {
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
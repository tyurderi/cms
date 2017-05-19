<template>
    <div class="user-form">
        <h1>Edit user</h1>
        
        <div class="form-container">
            <form @submit.prevent="submit">
                <div class="form-item">
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
                <div class="form-buttons">
                    <button type="submit" class="button">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import async from 'async';

export default {
    computed: {
        ...mapGetters({
            user: 'user/user',
            groups: 'user/groups'
        })
    },
    mounted()
    {
        let userID = this.$route.params.id;
        
        this.$progress.start();
        
        async.series([
            (done) =>
            {
                this.$http.post('api/user/listGroups')
                    .then(
                        response => {
                            this.$store.commit('user/setGroups', response.body.data);
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
                this.$http.post('api/user/get', { id: userID })
                    .then(
                        response => {
                            this.$store.commit('user/setUser', response.body.data);
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
                        if (!response.body.success)
                        {
                            this.$store.dispatch('error/push', response);
                            this.$progress.fail();
                        }
                        else
                        {
                            this.$progress.finish();
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
    padding: 10px;
    .form-container {
        padding: 10px;
        background: #fff;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        .form-item {
            margin: 0 0 10px 0;
            display: flex;
            flex-direction: row;
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
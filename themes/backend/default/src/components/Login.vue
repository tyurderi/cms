<template>
    <div class="login-container">
        <div class="login">
            <div class="title">
                Vuex CMS
            </div>
            <form method="post" v-on:submit.prevent="submit">
                <div class="form-item">
                    <label for="email">email:</label>
                    <input type="email" v-model="email" id="email" placeholder="email" />
                </div>
                <div class="form-item">
                    <label for="password">password:</label>
                    <input type="password" v-model="password" id="password" placeholder="password" />
                </div>
                <div class="form-buttons">
                    <checkbox v-model="keepLogin" label="Remember for 30 days" name="keep_login" />
                    <button type="submit" class="primary">login</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: 'login',
    data: () => ({
        email: '',
        password: '',
        keepLogin: false
    }),
    methods: {
        submit()
        {
            let me = this;

            me.$progress.start();

            me.$http.post('api/user/login', {
                email: me.email,
                password: me.password,
                keepLogin: me.keepLogin
            }).then((response) => {
                if (response.data.success)
                {
                    me.$permission.set(response.data.permissions);
                    me.$store.commit('site/set', response.data.sites);
                    
                    me.$progress.finish();
                    me.$events.emit('updateView', 'index');
                }
                else
                {
                    me.$progress.fail();
                    me.$toast.push({
                        text: 'The email or password you entered is wrong',
                        type: 'error',
                        delay: 3000
                    });
                }
            }, (error) => {
                me.$progress.fail();
                this.$store.dispatch('error/push', error)
            });
        }
    }
}
</script>

<style lang="less" scoped>
img {
    width: 300px;
    margin: 0 auto;
    display: block;
}
div.login-container {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    div.login {
        background: #fff;
        padding: 15px 15px 12px 15px;
        box-shadow: 0 1px 2px #c0392b;
        width: 350px;
        div.title {
            padding: 10px 10px 15px 10px;
            font-size: 24px;
            font-weight: 100;
            border-bottom: 1px solid #ddd;
            margin: 0 0 15px 0;
            text-align: center;
        }
        form {
            label {
                display: none;
            }
            input {
                width: 100%;
            }
            .form-buttons {
                height: 30px;
                button {
                    float: right;
                    height: 30px;
                    line-height: 32px;
                }
            }
        }
    }
}
</style>
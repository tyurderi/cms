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
                    <button type="submit" class="primary">login</button>
                </div>

                <checkbox v-model="keepLogin" label="Keep login for 30 days" name="keep_login" />
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
                me.$progress.finish();
                if (response.data.success)
                {
                    me.$events.emit('updateView', 'index');
                }
            }, (error) => {
                me.$progress.fail();
                console.log(error);
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
        padding: 15px;
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
            button {
                float: right;
            }
        }
    }
}
</style>
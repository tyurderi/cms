<template>
    <div class="login-container">
        <div class="login">
            <form method="post" v-on:submit.prevent="submit">
                <label for="email">email:</label>
                <input type="email" v-model="email" placeholder="email" />

                <label for="password">password:</label>
                <input type="password" v-model="password" placeholder="password" />

                <button type="submit">submit</button>

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
        keepLogin: false,
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
div.login {
    background: #fff;
    padding: 10px;
    width: 300px;
    height: 130px;
    position: absolute;
    top: 0; left: 0; bottom: 0; right: 0;
    margin: auto;
    box-shadow: 0 1px 2px #c0392b;
    label {
        display: none;
    }
    input {
        display: block;
        padding: 7px;
        width: 100%;
        text-align: center;
        margin: 0 0 5px 0;
        outline: 0 none;
        border: 1px solid #ccc;
        border-radius: 3px;
        &:focus {
            border-color: #e74c3c;
        }
    }
    button {
        background: #e74c3c;
        border: 0 none;
        outline: 0 none;
        padding: 6px 16px;
        border-radius: 3px;
        color: #fff;
        font-size: 14px;
        cursor: pointer;
        width: 100%;
        &:hover {
            background: #c0392b;
        }
    }
}
</style>
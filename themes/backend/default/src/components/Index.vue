<template>
    <div class="index">
        <div class="loader"></div>
    </div>
</template>

<script>
export default {
    name: 'index',
    mounted()
    {
        let me = this;

        me.$http.get('api/user/status').then((response) => {
            if (response.body.success === true)
            {
                // me.$emit('updateView', 'main');
            }
            else
            {
                me.$emit('updateView', 'login');
            }
        });
    }
}
</script>

<style lang="less" scoped>
.loader {
    position: absolute;
    width: 40px;
    height: 40px;
    top: 50%;
    left: 50%;
    margin: -20px 0 0 -20px;
    border: 3px solid rgba(255, 255, 255, 0.5);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .5s infinite linear;
}
</style>
<template>
    <div class="toast-container">
        <div class="toast-message"
             v-for="(item, key) in items" :key="key"
             :class="item.type">
            <div class="text" v-html="item.text"></div>
            <div class="close" @click="item.hidden = true">
                <i class="fa fa-times"></i>
            </div>
            
            <div class="bar" v-if="item.hideProgress !== null"
                 :style="{ width: item.hideProgress + '%' }"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'toast',
    computed: {
        items()
        {
            return this.$store.getters.TOASTS.filter(item => item.hidden === false);
        }
    }
}
</script>

<style lang="less" scoped>
.toast-container {
    position: fixed;
    right: 0;
    bottom: 0;
    .toast-message {
        position: relative;
        padding: 10px 10px;
        margin: 10px;
        color: #fff;
        max-width: 250px;
        display: flex;
        flex-direction: row;
        &.default {  background: #3498db; }
        &.success { background: #2ecc71; }
        &.error   { background: #e74c3c; }
        &.warning { background: #f1c40f; }
        div.text {
            flex: 1;
            margin: 0 10px;
        }
        div.close {
            cursor: pointer;
        }
        div.bar {
            position: absolute;
            left: 0;
            top: 0;
            height: 2px;
            background: rgba(0, 0, 0, 0.5);
            transition: all 0.25s;
        }
    }
}
</style>
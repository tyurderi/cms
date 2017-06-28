<template>
    <div class="toast-container">
        <transition-group name="toast" tag="div">
            <div class="toast-message"
                 v-for="(item, key) in items" :key="key"
                 :class="'is-' + item.type">
                <div class="text" v-html="item.text"></div>
                <div class="close" @click="item.hidden = true">
                    <i class="fa fa-times"></i>
                </div>
                
                <div class="bar" v-if="item.hideProgress !== null"
                     :style="{ width: item.hideProgress + '%' }"></div>
            </div>
        </transition-group>
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
        max-width: 400px;
        display: flex;
        flex-direction: row;
        align-items: center;
        &.is-default {  background: #3498db; }
        &.is-success { background: #2ecc71; }
        &.is-error   { background: #c0392b; }
        &.is-warning { background: #f1c40f; }
        div.text {
            flex: 1;
            margin: 0 10px;
        }
        div.close {
            cursor: pointer;
            background: rgba(0, 0, 0, 0.25);
            border-radius: 100%;
            width: 16px;
            height: 16px;
            text-align: center;
            line-height: 16px;
            font-size: 12px;
            opacity: 0.5;
            &:hover {
                opacity: 1;
            }
        }
        div.bar {
            position: absolute;
            left: 0;
            top: 0;
            height: 2px;
            background: rgba(0, 0, 0, 0.5);
            transition: all 0.5s linear;
        }
    }
}

.toast-enter-active {
    transition: all 0.25s;
}
.toast-enter, .list-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
.toast-leave-active {
    position: absolute;
}
</style>
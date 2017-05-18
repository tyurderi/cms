<template>
    <div class="modal-wrapper" :class="{ 'mode-fixed': mode === 'fixed' }">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-inner">
                <div class="modal" v-bind:style="{ width: width, height: height }">
                    <slot name="content"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'modal',
    props: [
        /**
         * Mode can be 'fixed' or 'absolute' and describes the positioning of the modal wrapper.
         * Default is 'absolute'
         */
        'mode',
        /**
         * Defines the width of the modal. Can be every valid css value.
         */
        'width',
        /**
         * Defines the height of the modal. Can be every valid css value.
         */
        'height'
    ]
}
</script>

<style lang="less" scoped>
.modal-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    &.mode-fixed {
        position: fixed;
    }
    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
    }
    .modal-container {
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
        .modal-inner {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            .modal {
                display: inline-block;
                text-align: left;
                background: #fff;
                box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
            }
        }
    }
}
</style>
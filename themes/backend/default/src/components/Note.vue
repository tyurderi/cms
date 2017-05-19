<template>
    <div class="note" :class="typeClass" v-if="!closed">
        <div class="icon">
            <i class="fa fa-times" v-if="type === 'error'"></i>
            <i class="fa fa-check" v-if="type === 'success'"></i>
            <i class="fa fa-exclamation-triangle" v-if="type === 'warning'"></i>
            <i class="fa fa-info-circle" v-if="type === 'info' || !type"></i>
        </div>
        <div class="text">
            {{text}}
        </div>
        <div class="close" @click="close">
            <i class="fa fa-times"></i>
        </div>
    </div>
</template>

<script>
export default {
    name: 'note',
    props: [
        /**
         * The text that should be shown.
         */
        'text',
        /**
         * The type of the note.
         * Can be 'error', 'warning', 'info' or 'success'
         * Default value is 'info'
         */
        'type'
    ],
    data: () => ({
        closed: false
    }),
    computed: {
        typeClass()
        {
            switch(this.type)
            {
                case 'error':
                    return 'error';
                case 'warning':
                    return 'warning';
                case 'success':
                    return 'success';
                default:
                    return 'info';
            }
        }
    },
    methods: {
        close()
        {
            this.closed = true;
        }
    }
}
</script>

<style lang="less" scoped>
.note {
    background: #3498db;
    color: #fff;
    margin: 0 0 5px 0;
    display: flex;
    flex-direction: row;
    &.error {
        background: #e74c3c;
    }
    &.warning {
        background: #f1c40f;
    }
    &.success {
        background: #2ecc71;
    }
    .icon {
        width: 40px;
        height: 42px;
        text-align: center;
        line-height: 42px;
    }
    .text {
        flex: 1;
        padding: 10px;
    }
    .close {
        cursor: pointer;
        width: 40px;
        height: 42px;
        text-align: center;
        line-height: 42px;
        background: rgba(0, 0, 0, 0.05);
        &:hover {
            background: rgba(0, 0, 0, 0.1);
        }
    }
}
</style>
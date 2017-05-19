<template>
    <div class="note" :class="typeClass">
        {{text}}
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
    }
}
</script>

<style lang="less" scoped>
.note {
    background: #3498db;
    color: #fff;
    padding: 10px;
    margin: 0 0 5px 0;
    &.error {
        background: #e74c3c;
    }
    &.warning {
        background: #f1c40f;
    }
    &.success {
        background: #2ecc71;
    }
}
</style>
<template>
    <div class="modal progress-modal">
        <modal mode="fixed" width="300px" height="150px">
            <div slot="content">
                <span class="label">
                    {{label}}
                </span>
                <span class="text">
                    {{text}}
                </span>
                <span class="loader-container">
                    <span class="loader"></span>
                </span>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        props: [
            /**
             * The question title.
             */
            'label',
            /**
             * The question description.
             */
            'text'
        ],
        data: () => ({

        }),
        methods: {
            accept()
            {
                this.$emit('accept');
            },
            reject()
            {
                this.$emit('reject');
            }
        }
    }
</script>

<style lang="less" scoped>
.modal.progress-modal {
    .label {
        display: block;
        height: 60px;
        padding: 10px;
        font-size: 28px;
        line-height: 60px;
        text-align: center;
    }
    .text {
        display: block;
        height: 50px;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }
    .loader-container {
        width: 100%;
        height: 40px;
        display: block;
        .loader {
            width: 20px;
            height: 20px;
            display: block;
            border: 2px solid #333;
            border-radius: 100%;
            border-left-color: transparent;
            animation: spin .5s infinite linear;
            margin: 0 auto;
        }
    }
}
</style>
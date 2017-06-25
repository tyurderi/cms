<template>
    <div class="modal question-modal">
        <modal mode="fixed" width="300px" height="150px">
            <div slot="content">
                <span class="label">
                    {{label}}
                </span>
                <span class="text">
                    {{text}}
                </span>
                <div class="buttons">
                    <button class="confirm" @click.prevent="accept">Yes</button>
                    <button class="abort" @click.prevent="reject">Abort</button>
                </div>
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
.modal.question-modal {
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
    .buttons {
        height: 40px;
        display: flex;
        flex-direction: row;
        button {
            flex: 1;
            height: 100%;
            border: 0 none;
            outline: 0 none !important;
            color: #fff;
            font-size: 14px;
            border-radius: 0;
            box-shadow: none;
            &:hover {
                cursor: pointer;
            }
        }
        button.confirm {
            background: #e74c3c;
            &:hover {
                background: darken(#e74c3c, 5);
            }
        }
        button.abort {
            color: #333;
            background: #eee;
            &:hover {
                background: #ddd;
            }
        }
    }
}
</style>
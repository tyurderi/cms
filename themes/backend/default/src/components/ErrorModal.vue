<template>
    <modal mode="fixed" width="500px" height="300px" v-if="hasError">
        <div slot="content">
            <div class="error">
                <span class="label">Oops! Something went wrong...</span>
                <span class="text">{{error.data}}</span>
                <div class="buttons">
                    <button class="ok" @click.prevent="close">
                        {{totalErrors > 1
                            ? ('Next (' + (totalErrors-1) + ')')
                            : 'OK'}}
                    </button>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'error-modal',
    computed: {
        ...mapGetters({
            hasError: 'error/hasError',
            error: 'error/currentError',
            totalErrors: 'error/total'
        })
    },
    methods: {
        close()
        {
            this.error.solved = true;
        }
    }
}
</script>

<style lang="less" scoped>
.error {
    height: 300px;
    display: flex;
    flex-direction: column;
    .label {
        display: block;
        padding: 10px;
        background: #e74c3c;
        color: #fff;
        font-size: 18px;
        height: 40px;
    }
    .text {
        flex: 1;
        display: block;
        padding: 5px;
        width: 100%;
        font: 14px Consolas, monospace;
        word-wrap: break-word;
        white-space: pre;
        overflow-y: auto;
    }
    .buttons {
        height: 40px;
        display: flex;
        flex-direction: row;
        button {
            flex: 1;
            height: 100%;
            border: 0 none;
            outline: 0 none;
            color: #fff;
            font-size: 14px;
            &:hover {
                cursor: pointer;
            }
        }
        button.ok {
            color: #333;
            background: #eee;
            &:hover {
                background: #ddd;
            }
        }
    }
}
</style>
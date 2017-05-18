<template>
    <div class="plugin-uninstaller" v-if="plugin">
        <modal mode="fixed" width="300px" height="150px">
            <div slot="content">
                <div class="confirm" v-if="!confirm">
                    <span class="label">
                        {{plugin.label}}
                    </span>
                    <span class="question">
                        Are you sure to uninstall this plugin?
                    </span>
                    <div class="buttons">
                        <button class="confirm" @click.prevent="accept">Yes</button>
                        <button class="abort" @click.prevent="reject">Abort</button>
                    </div>
                </div>
                <div class="install" v-if="confirm">
                    <span class="label">
                        {{plugin.label}}
                    </span>
                    <span class="text" v-if="!compiling">
                        Uninstalling plugin...
                    </span>
                    <span class="text" v-else>
                        Compiling themes...
                    </span>
                    <span class="loader-container">
                        <span class="loader"></span>
                    </span>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    props: ['plugin'],
    data: () => ({
        confirm: false,
        compiling: false,
    }),
    methods: {
        accept()
        {
            this.confirm = true;
            
            setTimeout(() => {
                this.compiling = true;
            }, 1000);
        },
        reject()
        {
            this.$emit('reject');
        }
    }
}
</script>

<style lang="less" scoped>
.confirm {
    .label {
        display: block;
        height: 60px;
        padding: 10px;
        font-size: 28px;
        line-height: 60px;
        text-align: center;
    }
    .question {
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
            border: 0 none;
            outline: 0 none;
            color: #fff;
            font-size: 14px;
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
.install {
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
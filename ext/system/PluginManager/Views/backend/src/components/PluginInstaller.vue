<template>
    <div class="plugin-installer" v-if="plugin">
        <div class="overlay"></div>
        
        <div class="popup-container">
            <div class="popup-inner">
                <div class="popup">
                    <div class="confirm" v-if="!confirm">
                        <span class="label">
                            {{plugin.label}}
                        </span>
                        <span class="question">
                            Are you sure to install this plugin?
                        </span>
                        <div class="buttons">
                            <button class="confirm" @click.prevent="accept">Yes</button>
                            <button class="abort" @click.prevent="reject">Abort</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['plugin'],
    data: () => ({
        confirm: false
    }),
    methods: {
        accept()
        {

        },
        reject()
        {
            this.$emit('reject');
        }
    }
}
</script>

<style lang="less" scoped>
.plugin-installer, .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}
.overlay {
    background: rgba(0, 0, 0, 0.25);
}
.popup-container {
    display: table;
    position: absolute;
    height: 100%;
    width: 100%;
    .popup-inner {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        .popup {
            display: inline-block;
            text-align: left;
            width: 300px;
            height: 150px;
            background: #fff;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        }
    }
}
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
</style>
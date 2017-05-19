<template>
    <div class="plugin-manager-modal" v-if="plugin">
        <modal mode="fixed" width="300px" height="150px">
            <div slot="content">
                <div class="confirm" v-if="!confirm">
                    <span class="label">
                        {{plugin.label}}
                    </span>
                    <span class="question">
                        {{questionText}}
                    </span>
                    <div class="buttons">
                        <button class="confirm" @click.prevent="accept">Yes</button>
                        <button class="abort" @click.prevent="reject">Abort</button>
                    </div>
                </div>
                <div class="progress" v-if="confirm">
                    <span class="label">
                        {{plugin.label}}
                    </span>
                    <span class="text">
                        {{actionText}}
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
    props: [
        /**
         * The plugin instance from the store.
         */
        'plugin',
        /**
         * What should this manager do?
         * Available actions are 'install', 'uninstall' or 'remove'
         */
        'action'
    ],
    data: () => ({
        confirm: false
    }),
    computed: {
        questionText()
        {
            switch (this.action)
            {
                case 'install':
                    return 'Are you sure to install this plugin?';
                case 'uninstall':
                    return 'Are you sure to uninstall this plugin?';
                case 'remove':
                    return 'Are you sure to remove this plugin?';
                default:
                    return 'Oh, I\'ve got an unknown action.';
            }
        },
        actionText()
        {
            switch (this.action)
            {
                case 'install':
                    return 'Installing plugin...';
                case 'uninstall':
                    return 'Uninstalling plugin...';
                case 'remove':
                    return 'Removing plugin...';
                default:
                    return 'Oh, I\'ve got an unknown action.';
            }
        }
    },
    methods: {
        accept()
        {
            this.confirm = true;
            
            if (this.hasOwnProperty(this.action) && typeof this[this.action]  === 'function')
            {
                this[this.action]();
            }
        },
        reject()
        {
            this.$emit('done');
        },
        install()
        {
            this.$http.post('api/plugin/install', { name: this.plugin.name })
                .then(
                    response => this.done(response),
                    response => this.$store.dispatch('error/push', response)
                );
        },
        uninstall()
        {
            this.$http.post('api/plugin/uninstall', { name: this.plugin.name })
                .then(
                    response => this.done(response),
                    response => this.$store.dispatch('error/push', response)
                );
        },
        remove()
        {
            this.$http.post('api/plugin/remove', { name: this.plugin.name })
                .then(
                    response => this.done(response),
                    response => this.$store.dispatch('error/push', response)
                );
        },
        done(response)
        {
            if (response.body.success)
            {
                this.confirm = false;
            }
            else
            {
                this.$store.dispatch('error/push', response);
            }

            this.$emit('done');
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
.progress {
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
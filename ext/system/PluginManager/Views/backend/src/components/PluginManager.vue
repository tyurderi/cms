<template>
    <div class="plugin-manager-modal" v-if="plugin">
        <v-question-modal v-if="!confirm" :label="plugin.label" :text="questionText"
                          @accept="accept" @reject="reject"></v-question-modal>

        <v-progress-modal v-if="confirm" :label="plugin.label" :text="actionText"></v-progress-modal>
    </div>
</template>

<script>
import VQuestionModal from '@/components/Modal/Question';
import VProgressModal from '@/components/Modal/Progress';

export default {
    components: {
        VQuestionModal,
        VProgressModal
    },
    props: [
        /**
         * The plugin instance from the store.
         */
        'plugin',
        /**
         * What should this manager do?
         * Available actions are 'install', 'uninstall', 'reinstall', 'update' or 'remove'
         */
        'action'
    ],
    data: () => ({
        confirm: false,
        questionText: '',
        actionText: '',
        doneText: ''
    }),
    watch: {
        plugin(plugin)
        {
            this.setTexts(this.action);
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
        reinstall()
        {
            this.setTexts('uninstall');
            
            this.$http.post('api/plugin/uninstall', { name: this.plugin.name })
                .then(
                    response => {
                        if (response.body.success === true)
                        {
                            this.setTexts('install');

                            this.$http.post('api/plugin/install', { name: this.plugin.name })
                                .then(
                                    response => {
                                        if (response.body.success === true)
                                        {
                                            this.setTexts('reinstall');
                                            this.done(response);
                                        }
                                        else
                                        {
                                            this.$store.dispatch('error/push', response)
                                        }
                                    },
                                    response => this.$store.dispatch('error/push', response)
                                )
                        }
                        else
                        {
                            this.$store.dispatch('error/push', response);
                        }
                    },
                    response => this.$store.dispatch('error/push', response)
                )
        },
        update()
        {
            this.$http.post('api/plugin/update', { name: this.plugin.name })
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
            this.confirm = false;
            
            if(!response.body.success)
            {
                this.$store.dispatch('error/push', response);
            }
            else
            {
                this.$toast.push({
                    text: this.doneText,
                    type: 'success',
                    delay: 3000
                })
            }

            this.$emit('done');
        },
        setTexts(action)
        {
            switch (action)
            {
                case 'install':
                    this.questionText = 'Are you sure to install this plugin?';
                    this.actionText   = 'Installing plugin...';
                    this.doneText     = 'The plugin were installed successfully';
                break;
                case 'uninstall':
                    this.questionText = 'Are you sure to uninstall this plugin?';
                    this.actionText   = 'Uninstalling plugin...';
                    this.doneText     = 'The plugin were uninstalled successfully';
                break;
                case 'reinstall':
                    this.questionText = 'Are you sure to reinstall this plugin?';
                    this.actionText   = 'Reinstalling plugin...';
                    this.doneText     = 'The plugin were reinstalled successfully';
                break;
                case 'update':
                    this.questionText = 'Are you sure to update this plugin?';
                    this.actionText   = 'Updating plugin...';
                    this.doneText     = 'The plugin were updated successfully';
                break;
                case 'remove':
                    this.questionText = 'Are you sure to remove this plugin?';
                    this.actionText   = 'Removing plugin...';
                    this.doneText     = 'The plugin were removed successfully';
                break;
                default:
                    this.questionText = 'Error: unknown action';
                    this.actionText   = 'Error: unknown action';
                    this.doneText     = 'Error: unknown action';
            }
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
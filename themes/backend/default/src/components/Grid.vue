<template>
    <div class="grid">
        <div class="head">
            <div class="title">
                {{data.length}} {{settings.headTitle}}
            </div>
            <ul class="actions">
                <li><a href="#" @click.prevent="dispatch('load')"><i class="fa fa-refresh"></i></a></li>
                <li><a href="#"><i class="fa fa-search"></i></a></li>
                <li><a href="#"><i class="fa fa-filter"></i></a></li>
                <li><a href="#" @click.prevent="dispatch('create')"><i class="fa fa-plus"></i></a></li>
            </ul>
        </div>
        <div class="body">
            <table class="list">
                <thead>
                <tr>
                    <th v-for="(col, key) in settings.columns" :key="key"
                        :width="col.width||'auto'">
                        {{col.label}}
                    </th>
                    
                    <th v-if="settings.actions" width="100">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, key) in data" :key="key">
                    <td v-for="(col, key) in settings.columns" :key="key">
                        {{getValue(item, key)}}
                    </td>
                    
                    <td class="actions" v-if="settings.actions">
                        <ul>
                            <li>
                                <a v-for="(action, key) in settings.actions" :key="key"
                                   href="#" @click.prevent="dispatchAction(action, item)">
                                    <i :class="action.iconCls"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        
        <v-question-modal v-if="progress.affectedItem && progress.isAsking === true"
                          :label="progress.action.ask.questionLabel"
                          :text="progress.action.ask.questionText"
                          @accept="confirmAction" @reject="progress.affectedItem = null"></v-question-modal>
        <v-progress-modal v-if="progress.affectedItem && progress.isProgressing === true"
                          :label="progress.action.ask.progressLabel"
                          :text="progress.action.ask.progressText"></v-progress-modal>
    </div>
</template>

<script>
import VQuestionModal from '@/components/Modal/Question';
import VProgressModal from '@/components/Modal/Progress';

export default {
    name: 'grid',
    props: {
        settings: {
            type: Object,
            required: true
        },
        data: {
            type: Array,
            required: true
        }
    },
    data: () => ({
        progress: {
            affectedItem: null,
            isAsking: false,
            isProgressing: false,
            data: null
        }
    }),
    mounted()
    {
        if (this.settings.autoLoad === true)
        {
            this.dispatch('load');
        }
    },
    methods: {
        /**
         * Helper method to dispatch events to the parent component.
         */
        dispatch(method, payload)
        {
            this.$emit(method, payload);
        },
        /**
         * Helper method to dispatch a row action with some features.
         */
        dispatchAction(action, item)
        {
            if (action.ask instanceof Object)
            {
                this.progress.affectedItem  = item;
                this.progress.isAsking      = true;
                this.progress.isProgressing = false;
                this.progress.action        = action;
            }
            else
            {
                this.$emit(action.name, item);
            }
        },
        /**
         * Helper method to confirm the execution of an action.
         */
        confirmAction()
        {
            this.progress.isAsking      = false;
            this.progress.isProgressing = true;
            
            this.$emit(this.progress.action.name, this.progress.affectedItem, () => {
                this.progress.affectedItem  = null;
                this.progress.isProgressing = false;
                this.progress.action        = null;
            });
        },
        /**
         * Get the value for a column. This method is used to support custom column renderer.
         *
         * @param item
         * @param key
         * @returns {*}
         */
        getValue(item, key)
        {
            if (item[key] !== undefined)
            {
                return item[key];
            }
            
            if (this.settings.renderer instanceof Object
                && this.settings.renderer[key] instanceof Function)
            {
                return this.settings.renderer[key](this, item);
            }
            
            return '';
        }
    },
    components: {
        VQuestionModal,
        VProgressModal
    }
}
</script>

<style lang="less" scoped>
.grid {
    position: relative;
    height: 100%;
}
table.list {
    background: #fff;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
    td.actions {
        ul {
            list-style: none;
            margin: 0;
            padding: 0;
            li {
                display: inline-block;
                margin: 0 5px 0 0;
                a {
                    transition: all 125ms;
                    &:hover {
                        color: #c0392b;
                    }
                }
            }
        }
    }
}
</style>
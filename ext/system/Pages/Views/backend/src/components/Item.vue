<template>
    <div class="section-item">
        <div class="item-header">
            <div class="item-actions" v-if="item.children">
                <ul>
                    <li v-if="collapsed === true">
                        <a href="#" @click.prevent="collapsed = false">
                            <i class="fa fa-plus-square"></i>
                        </a>
                    </li>
                    <li v-if="collapsed === false">
                        <a href="#" @click.prevent="collapsed = true">
                            <i class="fa fa-minus-square"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="item-title" @dblclick="startEdit">
                <template v-if="editing">
                    <input type="text" v-model="item.label" ref="itemLabel"
                           @blur="stopEdit"
                           @keydown.enter.prevent="stopEdit"
                           @keydown.esc.prevent="stopEdit" />
                </template>
                <template v-else>
                    <span>
                        {{item.label}}
                    </span>
                </template>
            </div>
            <div class="item-actions hidden">
                <ul>
                    <li><a href="#"><i class="fa fa-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o"></i></a></li>
                    <li><a href="#"><i class="fa fa-trash"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="item-children" v-if="item.children && collapsed === false">
            <page-item v-for="(children, key) in item.children" :key="key" :item="children"></page-item>
        </div>
    </div>
</template>

<script>
export default {
    name: 'page-item',
    props: [
        'item'
    ],
    data: () => ({
        collapsed: true,
        editing: false
    }),
    methods: {
        startEdit()
        {
            this.editing = true;

            this.$nextTick(() => {
                this.$refs.itemLabel.select();
                this.$refs.itemLabel.focus();
            })
        },
        stopEdit()
        {
            this.editing = false;
        }
    }
}
</script>

<style lang="less" scoped>
div.section-item {
    border-bottom: 1px dashed #ddd;
    &:last-child {
        border-bottom: none;
    }
    div.item-header {
        display: flex;
        flex-direction: row;
        height: 40px;
        div.item-title {
            flex: 1;
            span {
                display: block;
                padding: 10px;
            }
            input {
                height: 40px;
                border: 0 none;
                outline: 0 none;
                width: 100%;
                box-shadow: none !important;
                font-family: inherit;
            }
        }
        &:hover > div.item-actions.hidden {
            display: block;
        }
        div.item-actions {
            &.hidden {
                display: none;
            }
            ul {
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: row;
                li {
                    width: 40px;
                    height: 40px;
                    &:hover {
                        background: rgba(0, 0, 0, 0.025);
                    }
                    a {
                        width: 100%;
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        text-decoration: none;
                        color: #333;
                    }
                }
            }
        }
    }
    div.item-children {
        margin: 0 0 0 40px;
        div.section-item {
            border-top: 1px dashed #ddd;
        }
    }
}
</style>
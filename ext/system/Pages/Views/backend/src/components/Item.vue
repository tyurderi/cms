<template>
    <div class="section-item" :class="{ dragging: item.dragging }" :data-id="item.id">
        <div class="item-header">
            <div class="item-actions left relative item-drag">
                <ul v-if="children.length > 0 || item.dragging">
                    <template v-if="item.dragging === false">
                        <li v-if="item.collapsed === true">
                            <a href="#" @click.prevent="item.collapsed = false">
                                <i class="fa fa-plus-square"></i>
                            </a>
                        </li>
                        <li v-if="item.collapsed === false">
                            <a href="#" @click.prevent="item.collapsed = true">
                                <i class="fa fa-minus-square"></i>
                            </a>
                        </li>
                    </template>
                    <li v-else>
                        <a href="#"><i class="fa fa-arrows"></i></a>
                    </li>
                </ul>
                <div class="absolute item-actions"></div>
            </div>
            <div class="item-title relative" @dblclick="startEdit">
                <template v-if="item.editing">
                    <input type="text" v-model="item.label" ref="itemLabel"
                           @blur="stopEdit"
                           @keydown.enter.prevent="stopEdit"
                           @keydown.esc.prevent="stopEdit" />
                </template>
                <template v-else>
                    <span class="item-label">
                        {{item.label}}
                    </span>
                </template>
                <div class="item-title absolute"></div>
            </div>
            <div class="item-actions hidden relative">
                <ul>
                    <li @click.prevent="create"><a href="#"><i class="fa fa-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o"></i></a></li>
                    <li @click.prevent="remove"><a href="#"><i class="fa fa-trash"></i></a></li>
                </ul>
                <div class="item-actions absolute"></div>
            </div>
        </div>
        <div class="item-children" v-if="children.length > 0 && item.collapsed === false">
            <page-item v-for="(children, key) in children" :key="key" :item="children"></page-item>
        </div>
    </div>
</template>

<script>
export default {
    name: 'page-item',
    props: [
        'item'
    ],
    mounted()
    {
        if (this.item.editing === true)
        {
            this.startEdit();
        }
    },
    computed: {
        children()
        {
            return this.$store.getters['page/items']
                .filter(page => {
                    return page.parentID === this.item.id;
                })
                .sort((a, b) => {
                    return a.position - b.position;
                })
        }
    },
    methods: {
        startEdit()
        {
            this.item.editing = true;

            this.$nextTick(() => {
                this.$refs.itemLabel.select();
                this.$refs.itemLabel.focus();
            })
        },
        stopEdit()
        {
            if (this.item.editing === false)
            {
                return;
            }

            this.item.editing = false;
            this.$store.dispatch('page/save', this.item)
                .then(response => {
                    this.item.id = response.body.data.id;
                });
        },
        create()
        {
            this.item.collapsed = false;

            this.$store.commit('page/add', {
                id: -1,
                parentID: this.item.id,
                domainID: 1,
                type: 1,
                label: 'new page',
                created: new Date(),
                changed: new Date(),
                position: this.getPosition(),
                // pseudo data which will not be saved
                editing: true,
                dragging: false,
                collapsed: true
            })
        },
        remove()
        {
            this.$store.dispatch('page/remove', this.item);
        },
        getPosition()
        {
            let position = 0;
        
            this.children.forEach((child) => {
                if (child.position > position)
                {
                    position = child.position;
                }
            });
        
            return ++position;
        }
    }
}
</script>

<style lang="less" scoped>
.indent-ul(@n, @size, @i: 1) when(@i =< @n) {
    div.item-children {
        div.item-header {
            padding-left: @size * @i;
        }
        .indent-ul(@n, @size, @i + 1);
    }
}

.relative { position: relative; }
.absolute { position: absolute; pointer-events: none; }

div.section-item {
    border-bottom: 1px dashed #ddd;
    &.dragging {
        div.item-header {
            span {
                color: #ccc;
            }
            div.item-actions.hidden {
                display: none;
            }
        }
        div.item-children {
            display: none;
        }
    }
    &:last-child {
        border-bottom: none;
    }
    div.item-header {
        display: flex;
        flex-direction: row;
        height: 40px;
        div.item-title {
            flex: 1;
            &.absolute {
                top: 0;
                left: 0;
                background: rgba(52, 152, 219, 0.5);
                height: 40px;
                width: 100%;
            }
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
            &.absolute {
                top: 0;
                left: 0;
                background: rgba(155, 89, 182, 0.5);
                width: 100%;
                height: 40px;
            }
            &.hidden {
                display: none;
            }
            &.left {
                width: 40px;
                height: 40px;
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
        div.section-item:first-child {
            border-top: 1px dashed #ddd;
        }
    }
    .indent-ul(10, 40px);
}
</style>
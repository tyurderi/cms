<template>
    <div class="section-item" @mousedown="onMouseDown" v-if="item"
         :data-id="item.id"
         :class="{ dragging: item.dragging, pseudo: item.pseudo }"
         :style="{ top: pos.y + 'px', left: pos.x + 'px' }">
        <div class="item-header">
            <!--<div class="item-actions" v-if="item.children">
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
            </div>-->
            <div class="item-title">
                {{item.label}}
            </div>
            <div class="item-actions hidden">
                <ul>
                    <li><a href="#"><i class="fa fa-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o"></i></a></li>
                    <li><a href="#"><i class="fa fa-trash"></i></a></li>
                </ul>
            </div>
        </div>
        <!--<div class="item-children" v-if="item.children && collapsed === false && dragging === false">
            <page-item v-for="(children, key) in item.children" :key="key" :item="children"></page-item>
        </div>-->
    </div>
</template>

<script>
export default {
    name: 'page-item',
    props: [
        /**
         * The item itself.
         */
        'item',
        /**
         * The item siblings.
         */
        'siblings'
    ],
    data: () => ({
        collapsed: true,
        dragging: false,
        pseudoItem: null
    }),
    computed: {
        pos()
        {
            return this.item.pos || { x: 0, y: 0 }
        }
    },
    methods: {
        onMouseDown(e)
        {
            window.draggingItem = this.item;
            
            console.log('start');
            this.dragging = true;
            this.item.dragging = true;
            this.$forceUpdate();
            
            // create pseudo item
            this.pseudoItem = {
                id: -1,
                label: this.item.label,
                type: this.item.type,
                pseudo: true,
                pos: {
                    x: e.pageX - 250,
                    y: e.pageY
                }
            };

            this.siblings.push(this.pseudoItem);
            
            document.addEventListener('mousemove', this.onMouseMove);
            document.addEventListener('mouseup', this.onMouseUp);
            
            e.preventDefault();
        },
        onMouseMove(e)
        {
            this.pseudoItem.pos.x = e.pageX - 250;
            this.pseudoItem.pos.y = e.pageY;
            
            let element = this.getHoveringElement(e);
            
            if (element !== null)
            {
                let isBottom = e.offsetY >= element.offsetHeight / 2,
                    itemID   = parseInt(element.getAttribute('data-id')),
                    index    = this.siblings.findIndex(s => s && s.id === itemID),
                    item     = this.siblings[index];
                
                if (!item || item.id === window.draggingItem.id)
                {
                    return;
                }

                let currentIndex = this.siblings.indexOf(window.draggingItem),
                    targetIndex  = this.siblings.indexOf(item);

                this.siblings.splice(currentIndex, 1);
                
                if (isBottom)
                {
                    this.siblings.splice(targetIndex + 1, 0, window.draggingItem);
                }
                else
                {
                    this.siblings.splice(targetIndex, 0, window.draggingItem);
                }
            }
        },
        onMouseUp(e)
        {
            console.log('stop');
            this.dragging = false;
            this.item.dragging = false;
            
            this.siblings.forEach(s => s.dragging = false);
            this.$forceUpdate();
            this.$parent.$forceUpdate();
            
            document.removeEventListener('mousemove', this.onMouseMove);
            document.removeEventListener('mouseup', this.onMouseUp);
            
            // remove pseudo item
            this.siblings.splice(this.siblings.indexOf(this.pseudoItem), 1);
            this.pseudoItem = null;
            
            window.draggingItem = null;
        },
        getHoveringElement(e)
        {
            for (let i = 0; i < e.path.length; i++)
            {
                let element = e.path[i];
                
                if (element && element.className && element.className.indexOf('section-item') !== -1)
                {
                    return element;
                }
            }
            
            return null;
        }
    }
}
</script>

<style lang="less" scoped>
div.section-item {
    background: #fff;
    &:not(.pseudo) {
        border-bottom: 1px dashed #ddd;
        &:last-child {
            border-bottom: none;
        }
    }
    &.pseudo {
        position: absolute;
        pointer-events: none;
        border: 1px dashed #ddd;
        opacity: 0.5;
    }
    &.dragging {
        border: 1px dashed #333;
    }
    div.item-header {
        display: flex;
        flex-direction: row;
        height: 40px;
        div.item-title {
            flex: 1;
            padding: 10px;
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
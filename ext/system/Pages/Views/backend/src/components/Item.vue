<template>
    <div class="section-item">
        <div class="item-header">
            <div class="item-actions" v-if="item.children">
                <ul>
                    <li><a href="#"><i class="fa fa-minus-square"></i></a></li>
                </ul>
            </div>
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
        <div class="item-children" v-if="item.children">
            <page-item v-for="(children, key) in item.children" :key="key" :item="children"></page-item>
        </div>
    </div>
</template>

<script>
export default {
    name: 'page-item',
    props: [
        'item'
    ]
}
</script>

<style lang="less" scoped>
div.section-item {
        border-bottom: 1px dashed #ddd;
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
                border-bottom: none;
            }
        }
    }
</style>
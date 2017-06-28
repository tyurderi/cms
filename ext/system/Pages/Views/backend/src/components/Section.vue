<template>
    <section>
        <div class="section-header">
            <div class="section-title">
                {{section.label}}
            </div>
            <div class="section-actions">
                <ul>
                    <li><a href="#"><i class="fa fa-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o"></i></a></li>
                    <li><a href="#"><i class="fa fa-trash"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="section-items" v-if="section.children">
            <v-item v-for="(item, key) in section.children" :key="key" :item="item"></v-item>
        </div>
    </section>
</template>

<script>
import VItem from '@Pages/components/Item';

export default {
    name: 'page-section',
    props: [
        'section'
    ],
    components: {
        VItem
    }
}
</script>

<style lang="less" scoped>
section {
    background: #fff;
    box-shadow: 0 0 2px rgba(0, 0, 0, 0.125);
    div.section-header {
        display: flex;
        flex-direction: row;
        border-bottom: 1px solid #ddd;
        background: rgba(0, 0, 0, 0.025);
        div.section-title {
            flex: 1;
            padding: 10px;
            font-size: 18px;
            font-weight: 100;
            height: 40px;
            text-transform: uppercase;
        }
        div.section-actions {
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
    div.section-items {
    
    }
}
</style>
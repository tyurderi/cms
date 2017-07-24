<template>
    <section>
        <div class="section-header">
            <div class="section-title" @dblclick="startEdit">
                <template v-if="section.editing">
                    <input type="text" v-model="section.label" ref="sectionTitle"
                        @blur="stopEdit"
                        @keydown.enter.prevent="stopEdit"
                        @keydown.esc.prevent="stopEdit" />
                </template>
                <template v-else>
                    <span>
                        {{section.label}}
                    </span>
                </template>
            </div>
            <div class="section-actions">
                <ul>
                    <li @click.prevent="create"><a href="#"><i class="fa fa-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o"></i></a></li>
                    <li @click.prevent="remove"><a href="#"><i class="fa fa-trash"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="section-items" v-if="children">
            <v-item v-for="(item, key) in children" :key="key" :item="item"></v-item>
        </div>
    </section>
</template>

<script>
import VItem from '@Pages/components/Item';
import _ from 'lodash';

export default {
    name: 'page-section',
    props: [
        'section'
    ],
    data: () => ({

    }),
    mounted()
    {
        if (this.section.editing === true)
        {
            this.startEdit();
        }
    },
    computed: {
        children()
        {
            return this.$store.getters['page/items']
                .filter(page => {
                    return page.parentID === this.section.id;
                })
                .sort((a, b) => {
                    return a.position - b.position;
                })
        }
    },
    methods: {
        startEdit()
        {
            this.section.editing = true;

            this.$nextTick(() => {
                this.$refs.sectionTitle.select();
                this.$refs.sectionTitle.focus();
            })
        },
        stopEdit()
        {
            if (this.section.editing === false)
            {
                return;
            }

            this.section.editing = false;
            this.$store.dispatch('page/save', this.section)
                .then(response => {
                    this.section.id = response.body.data.id;
                });
        },
        create()
        {
            this.$store.commit('page/add', {
                id: -1,
                parentID: this.section.id,
                domainID: 1,
                type: 1,
                label: 'new page',
                created: new Date(),
                changed: new Date(),

                editing: true
            })
        },
        remove()
        {
            this.$store.dispatch('page/remove', this.section);
        }
    },
    components: {
        VItem
    }
}
</script>

<style lang="less" scoped>
section {
    background: #fff;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
    margin: 0 0 10px 0;
    &:last-child {
        margin: 0;
    }
    div.section-header {
        display: flex;
        flex-direction: row;
        border-bottom: 1px solid #ddd;
        div.section-title {
            flex: 1;
            font-size: 16px;
            font-weight: 500;
            height: 40px;
            text-transform: uppercase;
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
                text-transform: uppercase;
                font-size: 16px;
                font-weight: 500;
            }
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
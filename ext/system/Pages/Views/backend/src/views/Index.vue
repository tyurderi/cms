<template>
    <div class="page-list">
        <div class="head">
            <div class="title">
                Pages
            </div>
            <ul class="actions">
                <li>
                    <a href="#" @click.prevent="load"><i class="fa fa-refresh"></i></a>
                </li>
                <li>
                    <a href="#" @click.prevent="create"><i class="fa fa-plus"></i></a>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="dragging-item" v-if="draggingItem"
                 :style="{ (pos.y+16)+'px', left: (pos.x+16)+'px' }">
                {{ draggingItem.id }}#{{ draggingItem.label }}
            </div>

            <v-section v-for="(section, key) in sections" :key="key" :section="section"></v-section>
        </div>
    </div>
</template>

<script>
import VSection from '@Pages/components/Section';

export default {
    name: 'page-list',
    data: () => ({
        pos: { x: 0, y: 0 },
        icon: null
    }),
    computed: {
        sections()
        {
            return this.items.filter(page => {
                return !page.parentID && parseInt(page.type) === 3;
            })
        },
        draggingItem()
        {
            return this.items.get(this.draggingIndex);
        },
        draggingIndex()
        {
            return this.items.findIndex(item => item.dragging === true);
        },
        items()
        {
            return this.$store.getters['page/items'];
        }
    },
    mounted()
    {
        this.load();

        document.addEventListener('mousedown', this.dragStart.bind(this));
        document.addEventListener('mousemove', this.dragMove.bind(this));
        document.addEventListener('mouseup', this.dragEnd.bind(this));
    },
    beforeDestroy()
    {
        document.removeEventListener('mousedown', this.dragStart.bind(this));
        document.removeEventListener('mousemove', this.dragMove.bind(this));
        document.removeEventListener('mouseup', this.dragEnd.bind(this));
    },
    methods: {
        load()
        {
            this.$progress.start();

            this.$store.dispatch('page/load', () => {
                this.$progress.finish();
            });
        },
        create()
        {
            this.$store.commit('page/add', {
                id: -1,
                parentID: null,
                domainID: 1,
                type: 3,
                label: 'new section',
                created: new Date(),
                changed: new Date(),
                position: this.getPosition(),

                editing: true,
                dragging: false,
                collapsed: true
            })
        },
        getPosition()
        {
            let position = 0;

            this.sections.forEach((section) => {
                if (section.position > position)
                {
                    position = section.position;
                }
            });

            return ++position;
        },
        dragStart(e)
        {
            let target = this.getTarget(e, 'section-item');

            if (!target) return;

            clearTimeout(this.dragStartTimeout);

            this.dragStartTimeout = setTimeout(() => {

                this.pos.x = e.clientX;
                this.pos.y = e.clientY;

                let id     = target.getAttribute('data-id');

                if (!id) return;

                id = parseInt(id);

                let index  = this.items.findIndex(item => parseInt(item.id) === id),
                    item   = this.items.get(index);

                item.dragging = true;

                this.$store.commit('page/setAt', {
                    index,
                    item
                });
            }, 150);

            e.preventDefault();
        },
        dragMove(e)
        {
            if (!this.draggingItem) return;

            this.pos.x = e.clientX;
            this.pos.y = e.clientY;

            let target = this.getTarget(e, 'section-item');

            if (!target) {
                this.icon = 'none';
                return;
            }

            let id = target.getAttribute('data-id');

            if (!id) return;

            if (id === this.draggingItem.id)
            {
                if (this.needIndent(e, id))
                {
                    let sibling  = this.draggingItem.prev(),
                        position = this.getSiblingPosition(sibling);

                    if (sibling)
                    {
                        let fromIndex = this.items.findIndex(item => item.id === this.draggingItem.id),
                            toIndex   = this.items.findIndex(item => item.id === sibling.id),
                            fromItem  = this.items.get(fromIndex),
                            toItem    = this.items.get(toIndex);

                        fromItem.position = position;
                        fromItem.parentID = sibling.id;
                        toItem.collapsed  = false;

                        this.$store.commit('page/setAt', { index: fromIndex, item: fromItem });
                        this.$store.commit('page/setAt', { index: toIndex,   item: toItem   });
                    }
                }
                else if(this.needOutdent(e))
                {
                    let hoveringItem = this.items.find(n => n.id === id);

                    console.log(hoveringItem.id);
                }
            }
            else
            {
                let fromIndex = this.items.findIndex(item => item.id === this.draggingItem.id),
                    toIndex   = this.items.findIndex(item => item.id === id),
                    fromItem  = this.items.get(fromIndex),
                    toItem    = this.items.get(toIndex);

                if (toItem.id === this.draggingItem.parentID) {
                    return;
                }

                let pos = fromItem.position;

                fromItem.position = toItem.position;
                toItem.position   = pos;
                fromItem.parentID = toItem.parentID;

                this.$store.commit('page/setAt', { index: fromIndex, item: fromItem });
                this.$store.commit('page/setAt', { index: toIndex,   item: toItem   });
            }
        },
        dragEnd()
        {
            if (this.draggingItem)
            {
                this.draggingItem.dragging = false;

                this.$store.commit('page/setAt', {
                    index: this.draggingIndex,
                    item: this.draggingItem
                })
            }

            clearTimeout(this.dragStartTimeout);
        },
        getTarget(e, className)
        {
            if (e.target.classList && e.target.classList.contains(className))
            {
                return e.target;
            }

            for (let i = 0; i < e.path.length; i++)
            {
                if (e.path[i].classList && e.path[i].classList.contains(className))
                {
                    return e.path[i];
                }
            }

            return null;
        },
        needIndent(e)
        {
            let span = this.getTarget(e, 'item-label');

            if (span)
            {
                let rect = span.getBoundingClientRect();
                if (this.pos.x - rect.left > 50)
                {
                    return true;
                }
            }

            return false;
        },
        needOutdent(e)
        {
            let span = this.getTarget(e, 'item-label');

            if (span)
            {
                let rect = span.getBoundingClientRect();
                console.log(this.pos.x - rect.left);
                if (this.pos.x - rect.left <= 50)
                {
                    return true;
                }
            }

            return false;
        },
        getSiblingPosition(sibling)
        {
            if (!sibling) return -1;
            let position = 0;
            this.$store.getters['page/items']
                .filter(n => n.parentID === sibling.id)
                .forEach(n => {
                    if (n.position > position) {
                        position = n.position;
                    }
                });
            return position + 1;
        }
    },
    components: {
        VSection
    }
}
</script>

<style lang="less" scoped>
.page-list {
    position: relative;
    .dragging-item {
        position: fixed;
        padding: 10px 15px;
        background: #fff;
        border: 1px solid #ccc;
    }
}
</style>
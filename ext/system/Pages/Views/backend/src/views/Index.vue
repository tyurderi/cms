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
            <v-section v-for="(section, key) in sections" :key="key" :section="section"></v-section>
        </div>
    </div>
</template>

<script>
import VSection from '@Pages/components/Section';
import dom from '@Pages/util/dom';

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
            let target = this.getTarget(e, 'item-drag');

            if (!target) return;
            
            target = dom.up(target, 'section-item');

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
            
            let itemEl = this.getTarget(e, 'section-item');

            if (!itemEl) return;
            
            let id = itemEl.getAttribute('data-id');

            if (!id) return;
            
            let workingIndex = this.getItemIndex(this.draggingItem.id),
                currentIndex = this.getItemIndex(id),
                workingItem  = this.items.get(workingIndex),
                currentItem  = this.items.get(currentIndex);
            
            if (workingItem.id !== currentItem.id)
            {
                let currentPosition = workingItem.position;
                
                workingItem.position = currentItem.position;
                currentItem.position = currentPosition;
            }
            
            if (this.needIndent(itemEl)) {
                let prevItem = this.getPrevSibling(workingItem),
                    position = this.getSiblingPosition(prevItem);
    
                if (prevItem) {
                    console.log(prevItem.label);
                    let siblingIndex = this.getItemIndex(prevItem.id),
                        siblingItem  = this.items.get(siblingIndex);
                    
                    workingItem.position = position;
                    workingItem.parentID = prevItem.id;
                    prevItem.collapsed   = false;
                    
                    this.$store.commit('page/setAt', { index: siblingIndex, item: siblingItem });
                }
            } else if (this.needOutdent(itemEl)) {
                let parentIndex = this.getItemIndex(workingItem.parentID),
                    parentItem  = this.items.get(parentIndex);
                
                if (parentItem && parentItem.parentID === null) {
                    return;
                }
    
                workingItem.parentID = parentItem.parentID;
                workingItem.position = parentItem.position + 1; // TODO: Not working when parent item has next siblings
            }

            this.$store.commit('page/setAt', { index: workingIndex, item: workingItem });
            this.$store.commit('page/setAt', { index: currentIndex, item: currentItem });
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
        getItemIndex(id)
        {
            return this.items.findIndex(item => item.id === id);
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
        needIndent(itemEl)
        {
            let span = dom.down(itemEl, 'item-label');
            
            if (span.length > 0 && (span = span[0]) !== null)
            {
                let rect = span.getBoundingClientRect();
                if (this.pos.x - rect.left > 40)
                {
                    return true;
                }
            }

            return false;
        },
        needOutdent(itemEl)
        {
            let span = dom.down(itemEl, 'item-label');
    
            if (span.length > 0 && (span = span[0]) !== null)
            {
                let rect = span.getBoundingClientRect();
                if (this.pos.x - rect.left <= -40)
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
        },
        getPrevSibling(item) {
            return this.items
                .filter(n => n.parentID === item.parentID)
                .find((n, i, a) => {
                    return a[i+1]&&a[i+1].id===item.id;
                });
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
}
</style>
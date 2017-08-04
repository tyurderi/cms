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
            <div class="separator-container" ref="separatorContainer" v-if="draggingItem">
                <div class="separator" ref="separator"></div>
            </div>
            <v-section v-for="(section, key) in sections" :key="key" :section="section"></v-section>
        </div>
    </div>
</template>

<script>
import VSection from '@Pages/components/Section';

export default {
    name: 'page-list',
    computed: {
        sections()
        {
            return this.$store.getters['page/items'].filter(page => {
                return !page.parentID && parseInt(page.type) === 3;
            })
        },
        draggingItem()
        {
            return this.$store.getters['page/items'][this.draggingIndex]
        },
        draggingIndex()
        {
            return this.$store.getters['page/items'].findIndex(item => item.dragging === true);
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
                dragging: false
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

                let id     = target.getAttribute('data-id');

                if (!id) return;

                id = parseInt(id);

                let index  = this.$store.getters['page/items'].findIndex(item => parseInt(item.id) === id),
                    item   = this.$store.getters['page/items'][index];

                item.dragging = true;

                this.$store.commit('page/setAt', {
                    index,
                    item
                });

                this.$nextTick(() => this.moveSeparator(e, target));
            }, 150);

            e.preventDefault();
        },
        dragMove(e)
        {
            if (!this.draggingItem) return;

            let target = this.getTarget(e, 'section-item');

            if (!target) return;

            let id = target.getAttribute('data-id');

            if (!id) return;

            let direction = this.moveSeparator(e, target);
            let result    = this.getTargetByHover(id, direction);

            /*let items     = this.$store.getters['page/items'],
                fromIndex = items.findIndex(item => item.id === this.draggingItem.id),
                toIndex   = items.findIndex(item => item.id === id);

            let pos = items[fromIndex].position;

            items[fromIndex].position = items[toIndex].position;
            items[toIndex].position   = pos;

            this.$store.commit('page/setAt', { index: fromIndex, item: items[fromIndex] });
            this.$store.commit('page/setAt', { index: toIndex,   item: items[toIndex]   });*/
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
        moveSeparator(e, target)
        {
            let direction = e.offsetY >= target.offsetHeight / 2 ? 'bottom' : 'top',
                offsetTop = target.offsetTop - this.$refs.separatorContainer.offsetTop;

            if (direction === 'bottom')
            {
                offsetTop += target.offsetHeight;
            }

            this.$refs.separator.style.top = (offsetTop-1) + 'px';

            return direction;
        },
        getTargetByHover(id, direction)
        {
            let items = this.$store.getters['page/items'],
                item  = items.find(n => n.id === id);

            if (!item) {
                console.log('hovering item not found');
                return null;
            }

            if (item.id === this.draggingItem.id) {
                console.log('this drop is useless');
                return;
            }

            let siblings = items.filter(n => n.parentID === item.parentID);

            siblings.forEach((s, i) => {
                if (s.id !== id) return;
                if (direction === 'bottom' && siblings[i+1] && siblings[i+1].id === this.draggingItem.id
                    || direction === 'top' && siblings[i-1] && siblings[i-1].id === this.draggingItem.id) {
                    console.log('this drop is useless (2)');
                    return;
                }

                if (direction === 'top' && siblings[i-1] && siblings[i-1].id !== this.draggingItem.id) {
                    console.log('this drop looks great');
                    return;
                }
                if (direction === 'bottom' && siblings[i+1] && siblings[i+1].id !== this.draggingItem.id) {
                    console.log('this drop looks great (2)');
                    return;
                }
                if (direction === 'bottom' && !siblings[i+1]) {
                    console.log('this drop looks great (3)');
                    return;
                }
                if (direction === 'top' && !siblings[i-1]) {
                    console.log('this drop looks great (4)');
                    return;
                }
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
    .separator-container {
        position: relative;
        .separator {
            position: absolute;
            height: 0;
            border-bottom: 1px dashed #e74c3c;
            top: 0;
            left: 0;
            width: 100%;
        }
    }
}
</style>
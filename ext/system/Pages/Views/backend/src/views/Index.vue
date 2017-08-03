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

export default {
    name: 'page-list',
    data: () => ({

    }),
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

                editing: true
            })
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

            }, 150);

            e.preventDefault();
        },
        dragMove(e)
        {
            if (!this.draggingItem) return;

            let target = this.getTarget(e, 'section-item');

            if (!target) return;

            let id = target.getAttribute('data-id');

            if (id === null || id === this.draggingItem.id) return;

            let items     = this.$store.getters['page/items'],
                fromIndex = items.findIndex(item => item.id === this.draggingItem.id),
                toIndex   = items.findIndex(item => item.id === id);

            let pos = items[fromIndex].position;

            items[fromIndex].position = items[toIndex].position;
            items[toIndex].position   = pos;

            this.$store.commit('page/setAt', { index: fromIndex, item: items[fromIndex] });
            this.$store.commit('page/setAt', { index: toIndex,   item: items[toIndex]   });
        },
        dragEnd()
        {
            if (this.draggingItem) {
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
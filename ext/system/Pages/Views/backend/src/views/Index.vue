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
    computed: {
        sections()
        {
            return this.$store.getters['page/items'].filter(page => {
                return !page.parentID && parseInt(page.type) === 3;
            })
        }
    },
    mounted()
    {
        this.load();
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
        }
    },
    components: {
        VSection
    }
}
</script>
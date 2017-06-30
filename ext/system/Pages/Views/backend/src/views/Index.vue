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
        remoteSections: []
    }),
    computed: {
        sections()
        {
            return this.remoteSections;
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
            
            this.$http.get('api/page/list')
                .then(
                    response => {
                        this.remoteSections = response.body.data;
                        this.$progress.finish();
                    },
                    response => this.$store.dispatch('error/push', response)
                );
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
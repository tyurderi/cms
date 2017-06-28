<template>
    <div class="page-list">
        <v-section v-for="(section, key) in sections" :key="key" :section="section">
        
        </v-section>
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
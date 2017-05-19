<template>
    <div class="menu">
        <ul>
            <li v-for="item in items" :class="{ active: isActive(item) }">
                <router-link :to="item.url" @click="item.calls++">{{item.label}}</router-link>
            </li>
            <li>
                <a href="#" @click.prevent="logout">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'menu',
    data: () => ({

    }),
    computed: {
        ...mapGetters({
            items: 'menu/items'
        })
    },
    mounted()
    {
        let me = this;

        me.$store.dispatch('menu/load');
        
        // todo: Fix this workaround
        this.$router.beforeEach((to, from, next) => {
            this.$forceUpdate();
            next();
        })
    },
    methods: {
        isActive(item)
        {
            return item.url === this.$router.currentRoute.path;
        },
        logout()
        {
            let me = this;

            me.$progress.start();
            me.$http.get('api/user/logout').then(() => {
                me.$progress.finish();
                me.$events.emit('updateView', 'login');
            });
        }
    }
}
</script>
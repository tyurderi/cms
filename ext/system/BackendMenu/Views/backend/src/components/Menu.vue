<template>
    <div class="menu">
        <ul class="main">
            <li v-for="item in items" :class="{ active: isActive(item) }" v-permission="item.permissions">
                <router-link :to="item.url">
                    <span class="icon fa" :class="[ 'fa-' + (item.icon ? item.icon : 'question') ]"></span>
                    {{item.label}}
                </router-link>
                <menu-tree v-if="item.children.length > 0 && isActive(item)" :parent="item" :level="1"></menu-tree>
            </li>
            <li>
                <a href="#" @click.prevent="logout">
                    <span class="icon fa fa-sign-out"></span>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import MenuTree from '@BackendMenu/components/MenuTree';

export default {
    name: 'menu',
    components: {
        MenuTree
    },
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
            let equals = item.url === this.$router.currentRoute.path,
                starts = this.$router.currentRoute.path.indexOf(item.url) === 0;

            return equals || (starts && item.url.length > 1);
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
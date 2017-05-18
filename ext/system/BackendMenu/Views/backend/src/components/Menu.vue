<template>
    <div class="menu">
        <ul>
            <li v-for="item in items" :class="{ active: item.active }">
                <a :href="item.url">
                    {{ item.label }}
                </a>
            </li>
            <li>
                <a href="#" v-on:click.prevent="logout">
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
    },
    methods: {
        logout()
        {
            let me = this;
            
            me.$http.get('api/user/logout').then(() => {
                me.$events.emit('updateView', 'login');
            });
        }
    }
}
</script>
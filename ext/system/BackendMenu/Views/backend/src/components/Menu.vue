<template>
    <div class="menu">
        <ul>
            <li v-for="item in items" :class="{ active: item.active }">
                <a :href="item.url">
                    {{ item.label }}
                </a>
            </li>
            <li>
                <a href="#" v-on:click="logout">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'menu',
    data: () => ({
        items: []
    }),
    mounted()
    {
        let me = this;

        me.$events.on('menu.refresh', me.load.bind(me));
        me.$events.emit('menu.refresh');
    },
    methods: {
        load()
        {
            let me = this;
            
            me.$http.get('api/menu/list').then((response) => {
                me.items = response.data.data;
            });
        },
        logout(e)
        {
            let me = this;
            
            e.preventDefault();
            
            me.$http.get('api/user/logout').then(() => {
                me.$events.emit('updateView', 'login');
            });
        }
    }
}
</script>
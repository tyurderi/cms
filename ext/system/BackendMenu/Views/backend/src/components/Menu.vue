<template>
    <div class="menu">
        <ul>
            <li v-for="item in items" :class="{ active: item.active }">
                <a :href="item.url">
                    {{ item.label }}
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

        me.load();
    },
    methods: {
        load()
        {
            let me = this;
            
            me.$http.get('api/menu/list').then((response) => {
                me.items = response.data.data;
            });
        }
    }
}
</script>
<template>
    <div class="sidebar--menu">
        <ul>
            <li v-for="item in menu">
                {{ item.label }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'menu',
    data: () => ({
        menu: []
    }),
    mounted()
    {
        let me = this;

        me.$http.post('api/menu/list').then((response) => {
            me.menu = response.data.data;
        });
    }
}
</script>
<template>
    <ul class="sub-menu" :class="['level-' + level]">
        <li v-for="item in parent.children" :class="{ active: isActive(item) }">
            <router-link :to="item.url" v-tooltip.right="item.label">
                <span class="icon fa" :class="[ 'fa-' + (item.icon ? item.icon : 'question') ]"></span>
                {{item.label}}
            </router-link>
            <menu-tree v-if="item.children.length > 0 && isActive(parent)" :parent="item" :level="level+1"></menu-tree>
        </li>
    </ul>
</template>

<script>
export default {
    name: 'menu-tree',
    props: ['parent', 'level'],
    methods: {
        isActive(item)
        {
            let equals = item.url === this.$router.currentRoute.path,
                starts = this.$router.currentRoute.path.indexOf(item.url) === 0;

            return equals || (starts && item.url.length > 1);
        }
    }
}
</script>
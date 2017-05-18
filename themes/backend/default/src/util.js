import Vue from 'vue';

/**
 * A helper method to register multiple components at once.
 *
 * @param components
 */
export function registerComponents(components)
{
    components.forEach(cmp => {
        Vue.component(cmp.name, cmp);
    })
}
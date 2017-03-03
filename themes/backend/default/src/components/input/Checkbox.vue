<template>
    <span class="custom-checkbox">
        <input type="checkbox" :checked="value" @change="update($event.target.checked)" :id="name" />

        <label :for="name">{{ label }}</label>
    </span>
</template>

<script>
export default {
    name: 'checkbox',
    props: ['name', 'label', 'value'],
    methods: {
        update(value)
        {
            this.$emit('input', value);
        }
    }
}
</script>
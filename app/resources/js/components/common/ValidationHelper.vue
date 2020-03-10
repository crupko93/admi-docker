<template>
    <span>
        <v-icon>{{ icon }}</v-icon>
        <slot></slot>
    </span>
</template>

<script>

export default {
    name: 'ValidationHelper',

    props: ['value', 'validation'],

    computed: {
        icon () {
            this.validation;
            return !_.isUndefined(this.value) && !this.value
                ? 'indeterminate_check_box'
                : this.validation
                    ? 'check_box'
                    : 'check_box_outline_blank';
        }
    }
};
</script>

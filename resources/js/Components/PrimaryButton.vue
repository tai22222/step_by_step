<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="c-btn__primary"
    >
        <slot />
    </button>
</template>

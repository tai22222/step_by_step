<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="c-btn__secondary"
    >
        <slot />
    </button>
</template>

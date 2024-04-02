<script setup>
defineProps(['value']);
</script>

<template>
    <label class="c-text-input__label">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<script setup>
defineProps(['message']);
</script>

<template>
    <div v-show="message">
        <p class="c-text-input__error-message">
            {{ message }}
        </p>
    </div>
</template>

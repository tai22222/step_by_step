<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        class="c-text-input c-text-input__textarea"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    ></textarea>
</template>

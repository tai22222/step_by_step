<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: 'u-max-width__sm',
        md: 'u-max-width__md',
        lg: 'u-max-width__lg',
        xl: 'u-max-width__xl',
        '2xl': 'u-max-width__2xl',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="c-modal__transition-leave-active">
            <div v-show="show" class="c-modal" scroll-region>
                <transition
                    enter-active-class="c-modal__transition-enter-active"
                    enter-from-class="u-opacity-0"
                    enter-to-class="u-opacity-100"
                    leave-active-class="c-modal__transition-leave-active"
                    leave-from-class="u-opacity-100"
                    leave-to-class="u-opacity-0"
                >
                    <!-- ページ全体へのグレー背景 -->
                    <div v-show="show" class="c-modal__close" @click="close">
                        <div class="c-modal__close-background" />
                    </div>
                </transition>

                <transition
                    enter-active-class="c-modal__transition-enter-active"
                    enter-from-class="c-modal__transition-enter-from"
                    enter-to-class="c-modal__transition-enter-to"
                    leave-active-class="c-modal__transition-leave-active"
                    leave-from-class="c-modal__transition-leave-from"
                    leave-to-class="c-modal__transition-leave-to"
                >
                 <!-- 白背景 -->
                    <div
                        v-show="show"
                        class="c-modal__show"
                        :class="maxWidthClass"
                    >
                        <slot v-if="show" />
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>

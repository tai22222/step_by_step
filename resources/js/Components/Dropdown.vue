<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        default: 'right',
    },
    width: {
        default: '48',
    },
});

const open = ref(false);
const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'c-dropdown__width',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'c-dropdown__align-left';
    } else if (props.align === 'right') {
        return 'c-dropdown__align-right';
    } else {
        return 'c-dropdown__align-else';
    }
});
</script>

<template>
    <div class="c-dropdown">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <div v-show="open" class="c-dropdown__screen" @click="open = false"></div>

          <transition
              enter-active-class="c-dropdown__transition-enter-active"
              enter-from-class="c-dropdown__transition-enter-from"
              enter-to-class="c-dropdown__transition-enter-to"
              leave-active-class="c-dropdown__transition-leave-active"
              leave-from-class="c-dropdown__transition-leave-from"
              leave-to-class="c-dropdown__transition-leave-to"
          >
              <div
                  v-show="open"
                  class="c-dropdown__screen-open"
                  :class="[widthClass, alignmentClasses]"
                  style="display: none"
                  @click="open = false"
              >
                  <div class="c-dropdown__content">
                      <slot name="content" />
                  </div>
              </div>
          </transition>
    </div>
</template>

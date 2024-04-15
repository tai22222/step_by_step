<script setup>
// 各モジュールの読み込み
import { Link, useForm, usePage, } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'
import { ref, watch, computed, } from 'vue';

import axios from "axios";

// 親コンポーネントから値の受け取り
const props = defineProps({
  step: Object,
  index: Number,
  isChallengeStatus: String,
  stepStatus: Boolean,
});

// 目標達成時間の表示
const formattedEstimatedTime = computed(() => {
  const hours = props.step.estimated_time
  const years = Math.floor(hours / (24 * 12 * 30))
  const months = Math.floor((hours % (24 * 12 * 30)) / (24 * 30))
  const days = Math.floor((hours % (24 * 30)) / 24)
  const remainingHours = hours % 24

  const parts = []
  if (years > 0) {
    parts.push(`${years}年`)
  }
  if (months > 0) {
    parts.push(`${months}ヶ月`)
  }
  if (days > 0) {
    parts.push(`${days}日`)
  }
  if (remainingHours > 0 || parts.length === 0) { // 時間が0でも他がすべて0の場合は表示
    parts.push(`${remainingHours}時間`)
  }
  return parts.join(' ')
});

// statusの変更
const stepStatus = ref(props.stepStatus);
const emit = defineEmits(['update-status']);
const toggleStepStatus = async () => {
  try {
    const response = await axios.post(route('challenges.toggleStatus'), { project_id: props.step.project_id, step_id: props.step.id });
    if (response.data.success) {
      stepStatus.value = response.data.status;
      // 親コンポーネントにstepのidとstepの状態をemit
      emit('update-status', props.step.id, stepStatus.value);
      console.log('Stepの状態変更 成功');
    } else {
      console.log('Stepの状態変更 失敗:', response.data.message);
    }
  } catch (error) {
    console.error('Stepの状態変更時のエラー:', error);
  }
}

</script>
<template>
    <section>
        <form class="c-form c-step">
          <div class="p-step__list">
            <input v-if="isChallengeStatus"
                   type="checkbox"
                   v-model="stepStatus"
                   @change="toggleStepStatus"
                   class="c-checkbox p-checkbox">

            <Link :href="route('step.show', {project_id: step.project_id, step_id: index + 1 })"
                  class="p-step__list-link">
              <div class="u-margin__top-lg">
                <div class="p-step">
                  <span class="p-step__index">STEP. {{ index + 1 }}</span>
                  <span class="p-step__estimated_time">(推定時間: {{ formattedEstimatedTime }} )</span>
                </div>
                <div class="p-step">
                  タイトル:<span class="p-step__title"> {{ step.title }} </span>
                </div>
              </div>
            </Link>
          </div>
        </form>
    </section>
</template>
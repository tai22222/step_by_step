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
});

// 目標達成時間の表示
const formattedEstimatedTime = computed(() => {
  const hours = props.step.estimated_time
  const years = Math.floor(hours / (24 * 12 * 30))
  const months = Math.floor((hours % (24 * 12 * 30)) / (24 * 30))
  const days = Math.floor((hours % (24 * 30)) / 24)
  const remainingHours = hours % 24

  const parts = [];
  if (years > 0) {
    parts.push(`${years}年`);
  }
  if (months > 0) {
    parts.push(`${months}ヶ月`);
  }
  if (days > 0) {
    parts.push(`${days}日`);
  }
  if (remainingHours > 0 || parts.length === 0) {
    parts.push(`${remainingHours}時間`);
  }

  return parts.join(' ');
})

// inputの入力をリアクティブに反映(初期値はDBのchallengesテーブルから取得)
const stepStatus = ref(props.step.challenges.length > 0 ? true: false);
const emit = defineEmits(['update-status']);
const toggleStepStatus = async () => {
  try {
    const response = await axios.post(route('challenges.toggleStatus'), { project_id: props.step.project_id, step_id: props.step.id });
    if (response.data.success) {
      stepStatus.value = response.data.status;
      // 親コンポーネントにstepのidとstepの状態をemit
      emit('update-status', props.step.id, stepStatus.value);
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
        <form class="c-form p-step__min-height">
            <div class="u-margin__top-lg">
              <div class="p-step">
                <!-- ステップ完了時のチェック欄 -->
                <input v-if="isChallengeStatus"
                   type="checkbox"
                   v-model="stepStatus"
                   @change="toggleStepStatus"
                   class="c-checkbox p-checkbox">

                <span class="p-step__index">STEP. {{ index + 1 }}</span>
                <span class="p-step__estimated_time">(推定時間: {{ formattedEstimatedTime }} )</span>
              </div>

              <div class="p-step">
                タイトル:<span class="p-step__title"> {{ step.title }} </span>
              </div>

              <div class="c-form__content-full">
                Step内容:
                <div class="p-step-min-height"> {{ step.content }} </div>
              </div>
            </div>
        </form>
    </section>
</template>
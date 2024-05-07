<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import DetailProject from "./Partials/DetailProject.vue";
import DetailStep from "./Partials/DetailStep.vue";

import { Head, usePage, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

const props = defineProps({
  project: Object,
  step_id: String,
  isChallengeStatus: String,
});

// 選択したステップを管理し、表示する
const selectedStep = ref(props.step_id);
const showSelectedStep = (index) => {
  selectedStep.value = index;
};

// 進捗を計算
const progress = computed(() => {
  const completedCount = stepsStatus.value.filter(status => status.completed).length;
  return props.project.steps.length > 0 ? (completedCount / props.project.steps.length) * 100 : 0;
});

// stepのstatusでchallengesが空ではない配列をリアクティブに管理
const stepsStatus = ref(props.project.steps.map(step => ({
  id: step.id,
  completed: step.challenges.length > 0
})));

// 子コンポーネント(StepList)からstepのstatusの変化があった場合にemitされる
const updateStepStatus = (stepId, completed) => {
  const step = stepsStatus.value.find(s => s.id === stepId);
  if(step) {
    step.completed = completed;
  }
}
</script>

<template>
  <!-- ステップ詳細画面 -->
  <Head title="Detail Steps" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="c-header__main-title">{{ $t("Detail Steps") }}</h2>
    </template>

    <div class="u-padding__top-5xl u-padding__bottom-5xl">
      <div class="l-container c-contents">
        <div class="c-contents__inner u-margin__top-lg">
          <div v-if="isChallengeStatus">
            進捗:<span>{{ progress }}</span>%
          </div>
          <DetailProject 
              class="u-margin__top-lg"
              :project="project">
          </DetailProject>
        </div>

        <div class="c-contents__inner u-margin__top-lg">
          <div class="p-step__container">

            <!-- 各ステップ詳細への遷移カード -->
            <span
                v-for="(step, index) in project.steps"
                :key="index"
                class="p-step__card"
                :class="{ 'p-step__card-current': index + 1 == selectedStep }"
                @click="showSelectedStep(index+1)"
            >
              Step{{ index + 1 }}
            </span>
          </div>

          <!-- 各ステップの詳細 -->
            <div v-for="(step, index) in project.steps" 
                 :key="index"
                 v-show="index + 1 == selectedStep">
              <DetailStep
                  :step="step"
                  :index="index"
                  :step_id="selectedStep"
                  :isChallengeStatus="isChallengeStatus"
                  @update-status="updateStepStatus"
              >
              </DetailStep>
            </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
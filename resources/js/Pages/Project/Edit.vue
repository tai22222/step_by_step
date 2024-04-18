<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';

import EditProject from "./Partials/EditProject.vue";
import EditStep from "./Partials/EditStep.vue";

import { Head, usePage, useForm} from "@inertiajs/vue3";

const props = defineProps({
  project: Object,
  mustVerifyEmail: Boolean,
  status: String,
});

// 受け取りフォーム
const form = useForm({
  project: {
    title: props.project.title,
    category_id: props.project.category_id,
    content: props.project.content,
    estimated_time: props.project.estimated_time,
  },
  steps: props.project.steps.map(step => ({
    title: step.title,
    content: step.content,
    estimated_time: step.estimated_time,
    id: step.id,
  })),
});

// 子コンポーネント(Project)からデータを受け取り値を更新するメソッド
const updateProjectData = ( data ) => {
  form.project = data;
};

// ステップの追加・削除(todo スクロールの調整)
const addStep = () => {
  form.steps.push({ title: '', content: '', estimated_time: '' });
};

const removeStep = (index) => {
  form.steps.splice(index, 1);
};

// ステップデータの更新をハンドル
const handleUpdateStep = ({ stepIndex, stepData }) => {
  // 配列の特定のインデックスにあるオブジェクトを更新
  form.steps[stepIndex] = stepData;
  updateTotalEstimatedTime();
};

// 合計推定時間を計算
const updateTotalEstimatedTime = () => {
  const total = form.steps.reduce((acc, step) => acc + step.estimated_time, 0);
  form.project.estimated_time = total;
};

// CreateProject.vueとCreateStep.vueから受け取ったデータをformにまとめてLaravel側へ送信
const submitForm = () => {
  form.put(route('project.update', { id: props.project.id }));
};
</script>

<template>
  <Head title="Edit Steps" />
  <AuthenticatedLayout :flash="$page.props.flash">
    <template #header>
      <h2 class="c-header__main-title">{{ $t("Edit Steps") }}</h2>
    </template>
    <div class="u-padding__top-5xl u-padding__bottom-5xl">
      <form action="">
        <div class="l-container c-contents">

          <!-- プロジェクトの作成部分 -->
          <div class="c-contents__inner">
            <EditProject
                class="c-contents__width"
                :errors="form.errors"
                :categories="$page.props.categories"
                :projectData="form.project"
                @updateProjectData="updateProjectData"
            />
          </div>

          <!-- ステップの作成部分 todo 追加時のアニメーション -->
          <transition-group 
                enter-active-class="p-step__transition-enter-active"
                enter-from-class="p-step__transition-enter-from"
                enter-to-class="p-step__transition-enter-to"
                leave-active-class="p-step__transition-leave-active"
                leave-from-class="p-step__transition-leave-from"
                leave-to-class="p-step__transition-leave-to">
            <div class="c-contents__inner u-margin__top-lg"
               v-for="(step, index) in form.steps" :key="index">
              <EditStep 
                  class="c-contents__width"
                  :errors="form.errors"
                  :stepData="step"
                  :stepIndex="index"
                  :lastIndex="form.steps.length"
                  @updateStepData="handleUpdateStep"
                  @addStep="addStep"
                  @removeStep="removeStep"
              />
            </div>
          </transition-group>
        </div>
        <div class="l-container p-btn__position c-contents">
          <PrimaryButton 
              @click.prevent="submitForm"
              class="c-btn__position-fix">
            登録する
          </PrimaryButton>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

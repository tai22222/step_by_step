<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CreateProject from "./Partials/CreateProject.vue";
import CreateStep from "./Partials/CreateStep.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { reactive } from 'vue';

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
});

const { flash } = usePage().props;

// 受け取りフォーム
const form = reactive({
  project: {
    title: '',
    category: '',
    content: '',
    estimated_time: '',
  },
  steps: [
    { title: '', content: '', estimated_time: '' }, // 初期ステップ
  ],
});

// ステップの追加・削除
const addStep = () => {
  form.steps.push({ title: '', content: '', estimated_time: '' });
};
const removeStep = (index) => {
  form.steps.splice(index, 1);
};

// 子コンポーネント(Project)からデータを受け取り値を更新するメソッド
const updateProjectData = ( data ) => {
  form.project = data;
};

// ステップデータの更新をハンドル
const handleUpdateStep = ({ stepIndex, stepData }) => {
  // 配列の特定のインデックスにあるオブジェクトを更新
  form.steps[stepIndex] = stepData;
};

// ステップの目安達成時間の合計をプロジェクトの目安達成時間に設定

// CreateProject.vueとCreateStep.vueから受け取ったデータをformにまとめてLaravel側へ送信
const submitForm = () => {
  // ここでformデータをサーバーに送信
  console.log(form);
};
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout :flash="flash">
    <template #header>
      <h2 class="c-header__main-title">{{ $t("Profile") }}</h2>
    </template>

    <div class="u-padding__top-5xl u-padding__bottom-5xl">
      <form action="">
        <div class="l-container c-contents">

          <!-- プロジェクトの作成部分 -->
          <div class="c-contents__inner">
            <CreateProject
              class="c-contents__width"
              :projectData="form.project"
              @updateProjectData="updateProjectData"
            />
          </div>

          <!-- ステップの作成部分 todo 追加時のアニメーション -->
          <div class="c-contents__inner u-margin__top-lg"
               v-for="(step, index) in form.steps" :key="index">
            <CreateStep 
              class="c-contents__width"
              :stepData="step"
              :stepIndex="index"
              :lastIndex="form.steps.length"
              @updateStepData="handleUpdateStep"
              @addStep="addStep"
              @removeStep="removeStep"
            />
          </div>
        </div>
        <PrimaryButton @click.prevent="submitForm">
          登録する
        </PrimaryButton>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

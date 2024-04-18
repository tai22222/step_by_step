<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';

import DetailProject from "./Partials/DetailProject.vue";
import StepList from "./Partials/StepList.vue";

import { Head, Link, usePage, useForm} from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import axios from "axios";

// project : プロジェクト情報と、紐づくstep情報、challenges情報
// isChallengesStatus : ユーザがチャレンジしているかどうか
const props = defineProps({
  project: Object,
  isChallengeStatus: String,
});

// isChallengeStatusの状態で画面表示を切り替える
const isChallengeStatus = ref(props.isChallengeStatus);

// 未チャレンジからチャレンジへ変更する処理
const startChallenge = async () => {
  try {
    const response = await axios.post(route('challenges.start'), { project_id: props.project.id });
    if (response.data.success) {
      isChallengeStatus.value = 'in_progress';
      console.log('チャレンジへ変更 成功');
    } else {
      console.log('チャレンジへ変更 失敗:', response.data.message);
    }
  } catch (error) {
    console.error('チャレンジへ変更時にエラー:', error);
  }
}

// チャレンジから未チャレンジへ変更する処理
const stopChallenge = async () => {
  try {
    const response = await axios.post(route('challenges.stop'), { project_id: props.project.id});
    if (response.data.success) {
      isChallengeStatus.value = '';
      console.log('未チャレンジへ変更 成功');
    } else {
      console.log('未チャレンジへ変更 失敗:', response.data.message);
    }
  } catch (error) {
    console.error('未チャレンジへ変更時にエラー:', error);
  }
}

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
  <!-- todo チャレンジトグル チャレンジ人数、お気に入り人数 -->
    <Head title="Detail Project" />
    <AuthenticatedLayout >
        <template #header>
            <h2 class="c-header__main-title">{{ $t('Detail Project') }}</h2>
        </template>

        <div class="u-padding__top-5xl u-padding__bottom-5xl">
            <div class="l-container c-contents">

                <div class="c-contents__inner u-margin__top-lg ">
                  <div class="u-content__between">
                    <PrimaryButton 
                        v-if="!isChallengeStatus"
                        @click.prevent="startChallenge"
                        class="u-margin__bottom-lg u-position__left">
                      チャレンジする
                    </PrimaryButton>

                    <div v-if="isChallengeStatus"
                         class="u-position__left">
                      <PrimaryButton 
                          @click.prevent="stopChallenge"
                          class="u-margin__bottom-lg">
                        チャレンジを辞める
                      </PrimaryButton>

                      進捗:<span>{{ progress }}%</span>
                    </div>

                    <!-- 作成者のみ表示 -->
                    <div v-if="project.user_id === $page.props.auth.user.id"
                        class="u-content__inline-block">
                      <Link 
                          :href="route('project.edit', { id: project.id} )"
                          class="p-btn__edit">
                        編集
                      </Link>
                    </div>
                  </div>

                  <header>
                    <h2 class="c-header__title">{{ $t('Project Detail') }}</h2>

                      <p class="c-header__description">
                        {{ $t("A challenge allows you to incorporate this STEP into your own learning. Instead of estimating the learning process by yourself, you can proceed while adopting the optimal learning sequence that other users have already experienced. By pressing the 'Complete' button after clearing each STEP, you can check the overall progress of the learning process. Please consider incorporating it into what you are learning now or what you have already learned.") }}
                      </p>
                  </header>

                  <!-- プロジェクト詳細コンポーネント -->
                  <DetailProject class="u-margin__top-lg" 
                                 :project=project>
                  </DetailProject>
                </div>

                <!-- ステップ詳細コンポーネント -->
                <div class="c-contents__inner u-margin__top-lg">
                  <div v-for="(step, index) in project.steps" :key="index">
                    <StepList 
                        :step="step" 
                        :index="index"
                        :isChallengeStatus="isChallengeStatus"
                        :stepStatus="step.challenges.length > 0 ? true : false"
                        @update-status="updateStepStatus">
                    </StepList>
                  </div>
                  
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
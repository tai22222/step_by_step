<script setup>
// 各モジュールの読み込み
import { Link, useForm, usePage, } from '@inertiajs/vue3';
import { ref, watch, computed, } from 'vue';


// 親コンポーネントから値の受け取り
const props = defineProps({
  project: Object,
});

// 合計推定時間を年月日時間に変換
const formattedEstimatedTime = computed(() => {
  const hours = props.project.estimated_time;
  const years = Math.floor(hours / (24 * 12 * 30));
  const months = Math.floor((hours % (24 * 12 * 30)) / (24 * 30));
  const days = Math.floor((hours % (24 * 30)) / 24);
  const remainingHours = hours % 24;
  return `${years}年 ${months}ヶ月 ${days}日 ${remainingHours}時間`;
});

console.log(props);
</script>

<template>
    <section>
            <!-- プロジェクト タイトル -->
            <div class="p-card__title">
              {{ project.title }}
            </div>
            <div class="p-card__sub-content">
              カテゴリー : {{ project.category.name }}
            </div>
            <div class="p-card__sub-content">
              推定時間 : {{ formattedEstimatedTime }}
            </div>
            <div class="p-card__sub-content">
              Step数 : {{ project.steps.length }}
            </div>

            <!-- チャレンジしているかどうかのステータス -->
            <div v-if="project.challenges.length > 0" class="p-card__badge">
              チャレンジ中
            </div>
    </section>
</template>

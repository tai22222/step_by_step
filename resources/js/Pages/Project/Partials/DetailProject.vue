<script setup>
// 各モジュールの読み込み
import { Link, useForm, usePage, } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'
import { ref, watch, computed, } from 'vue';

// 親コンポーネントから値の受け取り
const props = defineProps({
  project: Object,
});

// 目標達成時間の表示
const formattedEstimatedTime = computed(() => {
  const hours = props.project.estimated_time
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
})

</script>
<template>
    <section>
        <header>
           <h2 class="c-header__title">{{ $t('Project Detail') }}</h2>

            <p class="c-header__description">
              {{ $t("A challenge allows you to incorporate this STEP into your own learning. Instead of estimating the learning process by yourself, you can proceed while adopting the optimal learning sequence that other users have already experienced. By pressing the 'Complete' button after clearing each STEP, you can check the overall progress of the learning process. Please consider incorporating it into what you are learning now or what you have already learned.") }}
            </p>
        </header>

        <form class="c-form">
            <!-- プロジェクト タイトル -->
            <div class="u-margin__top-lg p-project__title">
              {{ project.title }}
            </div>

            <!-- カテゴリ と 目安達成時間-->
            <div class="u-margin__top-lg p-project__sub-title">
              カテゴリー【 {{ project.category.name }} 】     推定時間【 {{ formattedEstimatedTime }} 】
            </div>

            <!-- プロジェクト 内容 -->
            <div class="u-margin__top-lg p-project__content">
              プロジェクト内容
              {{ project.content }}
            </div>
        </form>
    </section>
</template>
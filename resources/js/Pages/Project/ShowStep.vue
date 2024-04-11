<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';

import DetailProject from "./Partials/DetailProject.vue";
import DetailStep from "./Partials/DetailStep.vue";

import { Head, usePage, useForm} from "@inertiajs/vue3";

const props = defineProps({
  project: Object,
});


const toggleChallenge = () => {
  // apiの通信でチャレンジ中かどうかを管理
  console.log('クリック');
}

console.log(props);
</script>

<template>
  <!-- ステップ詳細画面 -->
    <Head title="Profile" />

    <AuthenticatedLayout >
        <template #header>
            <h2 class="c-header__main-title">{{ $t('Profile') }}</h2>
        </template>

        <div class="u-padding__top-5xl u-padding__bottom-5xl">
            <div class="l-container c-contents">

                <div class="c-contents__inner u-margin__top-lg">
                  <PrimaryButton @click.prevent="toggleChallenge"
                                 class="">
                    チャレンジする
                  </PrimaryButton>
                  チャレンジ人数
                  お気に入り人数
                  <DetailProject class="u-margin__top-lg" 
                                 :project=project>
                  </DetailProject>
                </div>

                <div class="c-contents__inner u-margin__top-lg">
                  <div v-for="(step, index) in project.steps" :key="index">
                    <DetailStep :step="step" :index="index">
                    </DetailStep>
                  </div>
                  
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
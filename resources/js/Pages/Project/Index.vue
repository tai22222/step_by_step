<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from "@/Components/Pagination.vue";

import { Head, Link, } from "@inertiajs/vue3";
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

import ProjectCard from "./Partials/ProjectCard.vue";

const props = defineProps({
  projects: Object,
  categories: Object,
  sort: String,
  category: String,
  search: String | Number,
});

// 並べ替えの値
const sortOrder = ref(props.sort? props.sort: 'newest');
// カテゴリ検索の値
const selectedCategory = ref(props.category? Number(props.category): 0);
// フリーワード
const searchQuery = ref('');
const sortProject = () => {
  // ソート条件と検索条件を格納
  const params = {};
  if (sortOrder.value) {
    params.sort = sortOrder.value;
  }
  if (selectedCategory.value && selectedCategory.value !== '0') {
    params.category = selectedCategory.value;
  }
  if (searchQuery.value) {
    params.search = searchQuery.value;
  }
  Inertia.get(route('project.index'), params);
}

// Twitter共有機能
const shareProject = () => {
  console.log('共有ボタンクリック');

  let shareUrl = "https://twitter.com/intent/tweet?text=" +
                 "◯月◯日までに（診断結果で出た本）を読み、感想をツイートします！" +
                 "%20%23NewSelf" +
                 "%20%23書籍診断アプリ";
                //  '&url' = + "https://www.google.com/?hl=ja" ;

                // location.href = shareUrl;
                window.open(shareUrl, '_blank');
}


console.log(props);
</script>

<template>
  <Head title="Index" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="c-header__main-title">{{ $t("Index") }}</h2>
    </template>
    <div class="u-padding__top-5xl u-padding__bottom-5xl">
      <div class="l-container">

        <!-- ソート(並び替え): 登録順(新しい・古い)、チャレンジ順 -->
        <div class="u-font__m u-margin__bottom-s u-margin__left-m">
          <div>
            <label for="sort">並び替え : </label>
            <select id="sort" 
                    v-model="sortOrder"
                    @change="sortProject"
                    class="c-text-input c-text-input__sort">
              <option value="newest">新しい順</option>
              <option value="oldest">古い順</option>
              <option value="most_challenged">チャレンジ数順</option>
            </select>
          </div>

          <!-- カテゴリ検索 -->
          <div>
            <label for="category">カテゴリ検索 : </label>
            <select id="category"
                    v-model="selectedCategory"
                    @change="sortProject"
                    class="c-text-input c-text-input__sort">
              <option value="0">カテゴリ選択</option>
              <option v-for="category in categories" 
                      :value="category.id" 
                      :key="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <!-- フリーワード検索 -->
          <div>
            <label for="search">フリーワード : </label>
            <input id="search"
                  type="text"
                  v-model="searchQuery" 
                  class="c-text-input c-text-input__sort u-margin__right-s"
                  placeholder="検索...">
            <SecondaryButton 
                @click="sortProject"
                class="c-btn__register">
              検索
            </SecondaryButton>
          </div>
          <div v-if="search">
            検索ワード：{{ search }}
          </div>
        </div>

        <div class="c-contents__inner c-card">
          <!-- カード型にして繰り返し表示 -->
          <div v-for="project in projects.data"
               :key="project.id"
               class="c-card__item">
            <!-- Twitter共有ボタン -->
            <button class="c-btn__share" @click="shareProject">
              <svg class="c-btn__share-icon" viewBox="0 0 20 20">
                <path d="m11.68 8.62 6.55-7.62h-1.55l-5.69 6.62-4.55-6.62h-5.25l6.88 10.01-6.88 7.99h1.55l6.01-6.99 4.8 6.99h5.24l-7.13-10.38zm-2.13 2.47-.7-1-5.54-7.92h2.39l4.47 6.4.7 1 5.82 8.32h-2.39l-4.75-6.79z"></path>
              </svg>
            </button>
            <Link :href="route('project.show', { id: project.id })">
              <ProjectCard :project="project"> </ProjectCard>
            </Link>
          </div>
        </div>

        <!-- ページネーション -->
        <Pagination :currentPage="projects.current_page" 
                    :totalPages="projects.last_page"
                    :pageLinks="projects.links">
        </Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

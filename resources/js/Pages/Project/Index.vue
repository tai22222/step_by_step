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

            <SecondaryButton @click="sortProject"
                             class="c-btn__register">検索</SecondaryButton>
          </div>
          <div v-if="search">
            検索ワード：{{ search}}
          </div>
        </div>

        <div class="c-contents__inner c-card">
          <!-- カード型にして繰り返し表示 -->
          <div v-for="project in projects.data"
               :key="project.id"
               class="c-card__item">
            <Link :href="route('project.show', { id: project.id })">
              <ProjectCard :project="project"> </ProjectCard>
            </Link>
          </div>
        </div>

        <!-- ページネーション -->
        <Pagination :currentPage="projects.current_page" 
                    :totalPages="projects.last_page"
                    :pageLinks="projects.links"
                    :path ="projects.path">
        </Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Pagination from "@/Components/Pagination.vue";

import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

import ProjectCard from "./Partials/ProjectCard.vue";

const props = defineProps({
  projects: Object,
  categories: Object,
  sort: String,
  category: String,
  search: String | Number,
});

const { ziggy } = usePage().props;

// 並べ替えの値
const sortOrder = ref(props.sort ? props.sort : "newest");
// カテゴリ検索の値
const selectedCategory = ref(props.category ? Number(props.category) : 0);
// フリーワード
const searchQuery = ref("");
// クエリパラメータをオブジェクトとして生成
const createQueryParams = computed(() => {
  const params = {};
  if (sortOrder.value) params.sort = sortOrder.value;
  if (selectedCategory.value && selectedCategory.value !== "0") params.category = selectedCategory.value;
  if (searchQuery.value) params.search = searchQuery.value;
  return params;
});

// Twitter共有機能
const shareProject = (projectId) => {
  console.log("共有ボタンクリック");
  console.log(projectId);
  const projectUrl = `${ziggy.url}/project/${projectId}`;
  const shareUrl =
    "https://twitter.com/intent/tweet?text=" +
    "この勉強手順をまねることで、学習期間の短縮ができました！◯ ◯を学んでいる方はぜひ取り入れてみてください！" +
    "%20%23勉強効率化" +
    "%20%23時間短縮" +
    "%20%23効率化アプリ" +
    "&url=" +
    encodeURIComponent(projectUrl);
  window.open(shareUrl, "_blank");
};
</script>

<template>
  <Head title="Index" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="c-header__main-title">{{ $t("Home") }}</h2>
    </template>
    <div class="u-padding__top-5xl u-padding__bottom-5xl">
      <div class="l-container">
        <!-- ソート(並び替え): 登録順(新しい・古い)、チャレンジ順 -->
        <div class="p-sort">
          <span class="p-sort__list">
            <label for="sort">並び替え : </label>
              <select
              id="sort"
              v-model="sortOrder"
              class="c-text-input c-text-input__sort"
            >
              <option value="newest">新しい順</option>
              <option value="oldest">古い順</option>
              <option value="most_challenged">チャレンジ数順</option>
            </select>
          </span>

          <!-- カテゴリ検索 -->
          <span class="p-sort__list">
            <label for="category">カテゴリ検索 : </label>
            <select
              id="category"
              v-model="selectedCategory"
              class="c-text-input c-text-input__sort"
            >
              <option value="0">カテゴリ選択</option>
              <option
                v-for="category in categories"
                :value="category.id"
                :key="category.id"
              >
                {{ category.name }}
              </option>
            </select>
          </span>

          <!-- フリーワード検索 -->
          <div>
            <input
              type="text"
              v-model="searchQuery"
              class="c-text-input c-text-input__sort c-text-input__width u-margin__right-s"
              placeholder="検索..."
            />

            <Link :href="route('project.index')"
                  :data="createQueryParams" 
                  method="get" 
                  preserve-scroll 
                  preserve-state>
                    <SecondaryButton class="c-btn__register">
                      検索
                    </SecondaryButton>
            </Link>
          </div>
          <div v-if="search">検索ワード：{{ search }}</div>
        </div>

        <div class="c-contents__inner c-card">
          <!-- カード型にして繰り返し表示 -->
          <div
            v-for="project in projects.data"
            :key="project.id"
            class="c-card__item"
          >
            <!-- Twitter共有ボタン -->
            <button class="c-btn__share" @click="shareProject(project.id)">
                <svg xmlns="http://www.w3.org/2000/svg" class="c-btn__share-icon icon icon-tabler icon-tabler-brand-x" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
              </svg>
            </button>
            <Link :href="route('project.show', { id: project.id })">
              <ProjectCard :project="project"> </ProjectCard>
            </Link>
          </div>
        </div>

        <!-- ページネーション -->
        <Pagination
          :currentPage="projects.current_page"
          :totalPages="projects.last_page"
          :pageLinks="projects.links"
        >
        </Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

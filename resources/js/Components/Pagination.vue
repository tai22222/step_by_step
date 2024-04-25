
<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
  currentPage: Number,
  totalPages: Number,
  pageLinks: Object,
});

// prevボタンは1ページ目表示時には非表示
// nextボタンはサウ集ページ表示時には非表示
// 現在のページと前後2ページ分を表示
const pagesToShow = computed(() => {
  const pages = []; // 表示するページ番号情報
  const padding = 2; // 現在のページの前後に表示するページ数
  let start = props.currentPage - padding;
  let end = props.currentPage + padding;

  if (start < 1) {
    end += 1 - start;
    start = 1;
  }

  if (end > props.totalPages) {
    if (start > 1) {
      start -= end - props.totalPages;
    }
    end = props.totalPages;
  }

  if (start > 1) {
    pages.push(1);
    if (start > 2) {
      pages.push(null); // ページが飛ぶ場合はnullを入れて省略記号を表示
    }
  }

  for (let page = start; page <= end; page++) {
    pages.push(page);
  }

  if (end < props.totalPages) {
    if (end < props.totalPages - 1) {
      pages.push(null);
    }
    pages.push(props.totalPages);
  }

  return pages;
});

// ページリンクの生成
const prevLink = computed(() => {
  return props.pageLinks.find(link => link.label === "&laquo; 前");
});

const nextLink = computed(() => {
  return props.pageLinks.find(link => link.label === "次 &raquo;");
});

const pageLinks = computed(() => {
  return props.pageLinks.filter(link => 
    link.label !== "&laquo; 前" && link.label !== "次 &raquo;"
  );
});
</script>

<template>
  <nav>
    <ul class="c-pagination">
      <!-- 前のページリンク -->
      <li v-if="currentPage > 1" class="c-pagination__item">
        <Link :href="prevLink.url" class="c-pagination__link">&laquo;</Link>
      </li>
      
      <!-- ページリンクの数を制限して表示 -->
      <li
        v-for="page in pagesToShow"
        :key="page"
        :class="{ 'c-pagination__item': true, active: page === currentPage }"
      >
        <!-- pageがnullではなく、pageLinkのindex番号と比較 -->
        <Link
          v-if="page && pageLinks[page - 1]"
          :href="pageLinks[page - 1].url"
          class="c-pagination__link"
          >{{ page }}</Link
        >

        <!-- pageがnullの場合 -->
        <span v-else class="c-pagination__not-link">...</span>
      </li>

      <!-- 次のページリンク -->
      <li v-if="currentPage < totalPages" class="c-pagination__item">
        <Link
          :href="nextLink.url"
          class="c-pagination__link"
          >&raquo;</Link
        >
      </li>
    </ul>
  </nav>
</template>

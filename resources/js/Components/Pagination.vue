
<script setup>
import { computed } from "vue";
import { Inertia } from "@inertiajs/inertia";

// ページのプロパティ、合計ページ数を受け取る
const props = defineProps({
  currentPage: Number,
  totalPages: Number,
  pageLinks: Object,
});

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

// ページ遷移
const visitPage = (url) => {
  Inertia.visit(url);
};
</script>


<template>
  <nav>
    <ul class="c-pagination">
      <!-- 前のページリンク -->
      <li v-if="currentPage > 1" class="c-pagination__item">
        <a
          href="#"
          @click="visitPage(prevLink.url)"
          class="c-pagination__link"
          >&laquo;</a
        >
      </li>
      <!-- ページリンクの数を制限して表示 -->
      <li
        v-for="page in pagesToShow"
        :key="page"
        :class="{ 'c-pagination__item': true, active: page === currentPage }"
      >
        <!-- pageがnullではなく、pageLinkのindex番号と比較 -->
        <a
          v-if="page && pageLinks[page - 1]"
          href="#"
          @click="visitPage(pageLinks[page - 1].url)"
          class="c-pagination__link"
          >{{ page }}</a
        >

        <!-- pageがnullの場合 -->
        <span v-else class="c-pagination__not-link">...</span>
      </li>

      <!-- 次のページリンク -->
      <li v-if="currentPage < totalPages" class="c-pagination__item">
        <a
          href="#"
          @click="visitPage(nextLink.url)"
          class="c-pagination__link"
          >&raquo;</a
        >
      </li>
    </ul>
  </nav>
</template>

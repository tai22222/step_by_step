
<script setup>
import { computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";

// ページのプロパティ、合計ページ数を受け取る
const props = defineProps({
  currentPage: Number,
  totalPages: Number,
  path: String,
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
      start -= (end - props.totalPages);
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

// ページ遷移
const visitPage = (page) => {
  Inertia.visit(props.path + '/?page=' +page);
};
</script>


<template>
  <nav>
    <ul class="c-pagination">
      <!-- 前のページリンク -->
      <li v-if="currentPage > 1"
          class="c-pagination__item">

        <a href="#" 
           @click="visitPage(currentPage - 1)"
           class="c-pagination__link">&laquo;</a>
      </li>

      <!-- ページリンクの数を制限して表示 -->
      <li v-for="page in pagesToShow" 
          :key="page" 
          :class="{'c-pagination__item': true, 'active': page === currentPage}">

        <a v-if="page" 
           href="#" 
           @click="visitPage(page)" 
           class="c-pagination__link">{{ page }}</a>

        <span v-else class="c-pagination__not-link">...</span>
      </li>

      <!-- 次のページリンク -->
      <li v-if="currentPage < totalPages"     
          class="c-pagination__item">

        <a href="#" 
           @click="visitPage(currentPage + 1)" 
           class="c-pagination__link">&raquo;</a>
      </li>
    </ul>
  </nav>
</template>

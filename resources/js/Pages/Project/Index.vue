<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link, } from "@inertiajs/vue3";

import ProjectCard from "./Partials/ProjectCard.vue";

const props = defineProps({
  projects: Object,
});
</script>

<template>
  <Head title="Index" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="c-header__main-title">{{ $t("Index") }}</h2>
    </template>
    <div class="u-padding__top-5xl u-padding__bottom-5xl">
      <div class="l-container c-contents">

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
                    :path ="projects.path">
        </Pagination>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

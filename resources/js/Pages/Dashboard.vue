<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from "@/Components/Pagination.vue";

import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

import ProjectCard from "./Project/Partials/ProjectCard.vue";

const props = defineProps({
  projects: Object,
  sort: String,
  category: String,
  search: String | Number,
  status: String,
});

const user = usePage().props.auth.user;

// クエリパラメータをオブジェクトとして生成
const createQueryParams = ( status = {status: 'mine'} ) => {
  const param = {
    status: status
  };
  return param;
};
console.log(props.projects);
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="c-header__main-title">{{ $t('My Page') }}</h2>
        </template>
        <div class="u-padding__top-5xl u-padding__bottom-5xl">
            <div class="l-container p-dashboard">

                <div class="p-dashboard__inner ">
                    <div class="p-dashboard__inner-text">
                      <!-- 編集リンク -->
                      <div class="p-dashboard__edit-link">
                          <Link :href="route('profile.edit')" class="p-btn__edit">編集</Link>
                        </div>

                        <!-- ユーザ情報表示エリア -->
                        <div class="p-dashboard__user-info">
                          <!-- アイコン画像 -->
                          <div v-if="user.icon_image" class="p-dashboard__user-icon u-margin__right-m">
                            <img :src="`/storage/${user.icon_image}`" :alt="user.name" class="c-image__preview"/>
                          </div>
                          <div v-else class="p-dashboard__user-icon u-margin__right-m">
                            <img src="/storage/default/no-image.jpg" :alt="user.name" class="c-image__preview"/>
                          </div>
                          <!-- ユーザ詳細情報 -->
                          <div class="p-dashboard__user-details">
                            <div>ユーザ名 : {{ user.name }}</div>
                            <div>メールアドレス : {{ user.email }}</div>
                            <div v-if="user.introduction">
                              <div>自己紹介</div>
                              <div class="p-dashboard__content">{{ user.introduction }}</div>
                            </div>
                            
                          </div>
                        </div>
                    </div>
                </div>

                <div class="p-dashboard__inner u-margin__top-m">
                    <div class="p-dashboard__inner-text">
                      <div class="p-step__container">

                        <!-- 各ステップ詳細への遷移カード -->
                        <Link class="p-step__card"
                              :class="{ 'p-step__card-current': status === 'mine' || status === null }"
                              :href="route('dashboard')"
                              :data="createQueryParams('mine')"
                        >My プロジェクト
                        </Link>

                        <Link class="p-step__card"
                              :class="{ 'p-step__card-current': status === 'challenging' }"
                              :href="route('dashboard')"
                              :data="createQueryParams('challenging')"
                        >チャレンジ中
                        </Link>

                        <Link class="p-step__card"
                              :class="{ 'p-step__card-current': status === 'completed' }"
                              :href="route('dashboard')"
                              :data="createQueryParams('completed')"
                        >達成したチャレンジ
                        </Link>
                      </div>

                      <div class="c-contents__inner c-card">
                        <!-- カード型にして繰り返し表示 -->
                        <div v-for="project in projects.data"
                            :key="project.id"
                            class="c-card__item">
                          <Link :href="route('project.show', { id: project.id })">
                            <div v-if="props.status === 'challenging'">
                              進捗 : {{ project.progress }} %
                            </div>
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

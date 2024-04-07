<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="c-header__main-title">{{ $t('My Page') }}</h2>
        </template>

        <div class="u-padding__top-5xl u-padding__bottom-5xl py-12">
            <div class="l-container p-dashboard">

              <!-- todo(Mypage作成) -->
                <div class="p-dashboard__inner">
                    <div class="p-dashboard__inner-text">
                      TOPページの作成
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

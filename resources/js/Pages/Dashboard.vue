<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="c-header__main-title">{{ $t('Dashboard') }}</h2>
        </template>

        <div class="py-12">
            <div class="l-container p-dashboard">
              <!-- todo -->
                <div class="p-dashboard__inner bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-dashboard__inner-text">TOPページの作成</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

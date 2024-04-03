<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="c-header__main-title">{{ $t('Profile') }}</h2>
        </template>

        <div class="u-padding__top-5xl u-padding__bottom-5xl">
            <div class="l-container c-contents">
                <div class="c-contents__inner">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="c-contents__width"
                    />
                </div>

                <div class="c-contents__inner u-margin__top-lg">
                    <UpdatePasswordForm class="c-contents__width" />
                </div>

                <div class="c-contents__inner u-margin__top-lg">
                    <DeleteUserForm class="c-contents__width" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

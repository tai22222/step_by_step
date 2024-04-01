<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="l-wrapper c-guest-layout">
        <div>
            <Link href="/">
                <ApplicationLogo class="c-application_logo" />
            </Link>
        </div>

        <div
            class="l-container c-guest-layout__contents"
        >
            <slot />
        </div>
    </div>
</template>

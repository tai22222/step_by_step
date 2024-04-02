<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="l-wrapper">

            <nav class="l-header">
                <!-- ナビゲーションメニュー -->
                <div class="l-container p-nav">
                    <div class="p-nav__inner">

                        <!-- ヘッダーレフト -->
                        <div class="p-nav_left">
                            <!-- ロゴ -->
                            <div class="p-nav__left-logo">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="c-application_logo"
                                    />
                                </Link>
                            </div>

                            <!-- ナビゲーションリンク(スマホは非表示) -->
                            <div class="p-nav__left-link">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <!-- ヘッダーライト -->
                        <div class="p-nav__right">
                            <!-- ドロップダウン -->
                            <div class="p-nav__right-dropdown">
                                <Dropdown align="right" width="48">
                                    <template #trigger>

                                        <span class="p-nav__icon">
                                            <button type="button" class="p-nav__dropdown-text">
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="p-nav__dropdown-menu"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- ハンバーガーメニュー -->
                        <div class="p-hamburger">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="p-hamburger__btn"
                            >
                                <svg class="p-hamburger__btn-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            'is-hidden': showingNavigationDropdown,
                                            'is-inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            'is-hidden': !showingNavigationDropdown,
                                            'is-inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- レスポンシブ ナビゲーション メニュー -->
                <div
                    :class="{ 'is-block': showingNavigationDropdown, 'is-hidden': !showingNavigationDropdown }"
                    class="p-nav__responsible"
                >
                    <div class="p-nav__responsible-title">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- レスポンシブ ナビゲーション セッティング -->
                    <div class="p-nav__responsible-setting">
                        <div class="p-nav__responsible-user">
                            <div class="p-nav__responsible-user__name">{{ $page.props.auth.user.name }}</div>
                            <div class="p-nav__responsible-user__email">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="p-nav__responsible-routing">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- ヘッダー(現在のページ情報) -->
            <header class="l-header p-header" v-if="$slots.header">
                <div class="l-container c-header__title">
                    <slot name="header" />
                </div>
            </header>

            <!-- 各ページのコンポーネント -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const props = defineProps({
  flash: Object,
});

const { flash } = usePage().props;
// フラッシュメッセージに関する定義
const successMessage = ref('');
const errorMessage = ref('');
const isShowMessage = ref(false);

// フラッシュメッセージをセットアップする関数
const setupFlashMessages = () => {
  if (flash.success) {
    successMessage.value = flash.success;
    isShowMessage.value = true; // 成功メッセージを表示
  }
  
  if (flash.error) {
    errorMessage.value = flash.error;
    isShowMessage.value = true; // エラーメッセージを表示
  }

  // 一定時間後にメッセージを非表示にする
  setTimeout(() => {
    isShowMessage.value = false;
  }, 3000);
};

// コンポーネントがマウントされた時にフラッシュメッセージをセットアップする
onMounted(() => {
  if (flash.success || flash.error) {
    setupFlashMessages();
  }
});
</script>

<template>
    <div>
        <div class="l-wrapper">

            <!-- フラッシュメッセージ -->
              <!-- サクセスメッセージ -->
            <transition 
                  enter-active-class="c-flash__enter-active"
                  enter-from-class="c-flash__enter-from" 
                  leave-active-class="c-flash__leave-active"
                  leave-to-class="c-flash__leave-to">
              <div v-if="isShowMessage && successMessage"
                  class="c-flash__success"
                  role="alert">
                {{ successMessage }}
              </div>
            </transition>
              <!-- エラーメッセージ -->
            <transition 
                  enter-active-class="c-flash__enter-active"
                  enter-from-class="c-flash__enter-from" 
                  leave-active-class="c-flash__leave-active"
                  leave-to-class="c-flash__leave-to">
              <div v-if="isShowMessage && errorMessage"
                  class="c-flash__error"
                  role="alert">
                {{ errorMessage }}
              </div>
            </transition>

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
                                    {{ $t('My Page') }}
                                </NavLink>
                                <NavLink :href="route('project.create')" :active="route().current('project.create')">
                                    {{ $t('create steps') }}
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
                                        <DropdownLink :href="route('profile.edit')"> {{ $t('Profile') }} </DropdownLink>
                                        <DropdownLink :href="route('project.create')"> {{ $t('create steps') }} </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">{{ $t('Log Out') }}</DropdownLink>
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
                            {{ $t('My Page') }}
                        </ResponsiveNavLink>
                    </div>

                    <!-- レスポンシブ ナビゲーション セッティング -->
                    <div class="p-nav__responsible-setting">
                        <div class="p-nav__responsible-user">
                            <div class="p-nav__responsible-user__name">{{ $page.props.auth.user.name }}</div>
                            <div class="p-nav__responsible-user__email">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="p-nav__responsible-routing">
                            <ResponsiveNavLink :href="route('profile.edit')"> {{ $t('Profile') }} </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('project.index')"> {{ $t('Step') }} </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">{{ $t('Log Out') }}</ResponsiveNavLink>
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

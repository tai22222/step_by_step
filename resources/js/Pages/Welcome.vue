<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="l-wrapper p-welcome__wrapper
    relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

        <div v-if="canLogin" class="p-welcome__header">
            <Link
                v-if="$page.props.auth.user"
                :href="route('project.index')"
                class="p-link"
                >{{ $t('Home') }}
              </Link>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="p-link"
                    >{{ $t('Log in') }}
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="p-link u-margin__left-m"
                    >{{ $t('Register') }}
                  </Link>
            </template>
        </div>

        <div class="l-container u-padding-2xl">
            <div class="p-welcome__main-title">
                <h1>「あなたの人生のSTEPを共有しよう」</h1>
                <div>学習の「順序」と「内容」を記録し共有するSTEP</div>
                <div>~~ STEPを取り入れることで、学習効率を最大限にサポート ~~</div>
            </div>

            <div class="u-margin__top-5xl">

                <div class="p-welcome__inner">
                    <a
                        :href="route('project.create')"
                        class="p-welcome__card"
                    >
                        <div>
                            <div class="p-welcome__icon">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                <path d="M13.5 6.5l4 4" />
                                <path d="M16 19h6" />
                                <path d="M19 16v6" />
                              </svg>
                            </div>

                            <h2 class="p-welcome__title">学習手順をシェア</h2>
                            <p class="p-welcome__content">
                                今まで取り組んできた学習で、自分なりの効率のいい学習手順を共有しましょう！まずは新規登録後にステップの作成から学習手順の詳細を記入してください！
                            </p>
                        </div>
                    </a>

                    <a
                        :href="route('project.index')"
                        class="p-welcome__card"
                    >
                        <div>
                            <div class="p-welcome__icon">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                              <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" />
                              <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
                              <path d="M18.5 19.5l2.5 2.5" />
                            </svg>
                            </div>

                            <h2 class="p-welcome__title">学習に取り入れる</h2>
                            <p class="p-welcome__content">
                                他のユーザが作成した、学習手順を自分の現在取り組んでいる学習に取り入れることで効率よく学習を進めていきましょう！
                            </p>
                        </div>
                    </a>

                    <a
                        :href="route('dashboard')"
                        class="p-welcome__card"
                    >
                        <div>
                            <div class="p-welcome__icon">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
                            </svg>
                            </div>

                            <h2 class="p-welcome__title">プロジェクト</h2>
                            <p class="p-welcome__content">
                                Laravel News is a community driven portal and newsletter aggregating all of the latest
                                and most important news in the Laravel ecosystem, including new package releases and
                                tutorials.
                            </p>
                        </div>
                    </a>

                    <div
                        class="p-welcome__card"
                    >
                        <div>
                            <div class="p-welcome__icon">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                              <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                              <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                              <path d="M11 6l9 0" />
                              <path d="M11 12l9 0" />
                              <path d="M11 18l9 0" />
                            </svg>
                            </div>

                            <h2 class="p-welcome__title">ステップ</h2>
                            <p class="p-welcome__content">
                                Laravel News is a community driven portal and newsletter aggregating all of the latest
                                and most important news in the Laravel ecosystem, including new package releases and
                                tutorials.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

import '../scss/style.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import { InertiaProgress } from '@inertiajs/progress';
import { createI18n } from 'vue-i18n'
import ja from './locales/ja.json';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// './Pages' ディレクトリ内のすべての `.vue` ファイルを解決するための関数を定義
const pages = require.context('./Pages', true, /\.vue$/);

// i18nでの多言語化対応のための定義
const i18n = createI18n({
  locale: window.App.locale,
  fallbackLocale: 'en',
  messages: {
    ja,
  },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
      const importPage = pages(`./${name}.vue`);
      return importPage.default;
    }, 
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(i18n)
            .mount(el);
    },
});

// プログレスバーのカラー定義
InertiaProgress.init({
  color: '#4B5563', // ここでプログレスバーの色を設定
});
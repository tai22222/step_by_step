const { VueLoaderPlugin } = require('vue-loader');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const webpack = require('webpack');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

module.exports = {
    mode: 'development',
    entry: './resources/js/app.js',
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'js/app.js'
    },
    module: {
        rules: [
            // vueのtemplateセクションをコンパイル
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            // sass,vueのstyleセクションをコンパイル(ファイルやurlは未処理)
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            // vueのscriptセクションをコンパイル
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            filename: 'css/app.css',
        }),
        new webpack.DefinePlugin({
          __VUE_OPTIONS_API__: JSON.stringify(true),
          __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
          __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(true)
        }),
        // 既存のプラグイン設定...
        new WebpackManifestPlugin({
          fileName: 'mix-manifest.json',
          publicPath: '/',
          writeToFileEmit: true
        })
    ],
    resolve: {
        alias: {
          '@': path.resolve(__dirname, 'resources/js'),
          vue$: 'vue/dist/vue.esm-bundler.js'
        },
        extensions: ['.js', '.vue', '.json']
    },
    // ビルドのタイミングを調整
    watchOptions: {
      ignored: /node_modules/, // 監視しないディレクトリ
      poll: 15000 // 監視間隔（ミリ秒）
  }
};

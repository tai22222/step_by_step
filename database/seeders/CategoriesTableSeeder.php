<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $categories = [
        ['name' => 'プログラミング', 'sort_order' => 1],
        ['name' => 'データサイエンス', 'sort_order' => 2],
        ['name' => 'ウェブ開発', 'sort_order' => 3],
        ['name' => 'モバイルアプリ開発', 'sort_order' => 4],
        ['name' => 'クラウドコンピューティング', 'sort_order' => 5],
        ['name' => 'サイバーセキュリティ', 'sort_order' => 6],
        ['name' => 'AI・機械学習', 'sort_order' => 7],
        ['name' => 'ビジネススキル', 'sort_order' => 8],
        ['name' => 'マーケティング', 'sort_order' => 9],
        ['name' => 'デザイン', 'sort_order' => 10],
        ['name' => 'UX/UIデザイン', 'sort_order' => 11],
        ['name' => 'グラフィックデザイン', 'sort_order' => 12],
        ['name' => '英語', 'sort_order' => 13],
        ['name' => '中国語', 'sort_order' => 14],
        ['name' => 'その多言語', 'sort_order' => 15],
        ['name' => '数学', 'sort_order' => 16],
        ['name' => '物理学', 'sort_order' => 17],
        ['name' => '化学', 'sort_order' => 18],
        ['name' => '生物学', 'sort_order' => 19],
        ['name' => '歴史', 'sort_order' => 20],
        ['name' => '心理学', 'sort_order' => 21],
        ['name' => '教育学', 'sort_order' => 22],
        ['name' => '金融・経済学', 'sort_order' => 23],
        ['name' => '健康・美容', 'sort_order' => 24],
        ['name' => 'スポーツ・フィットネス', 'sort_order' => 25],
        ['name' => 'その他', 'sort_order' => 999],
    ];

    DB::table('categories')->insert($categories);
    }
}

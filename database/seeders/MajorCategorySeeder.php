<?php

namespace Database\Seeders;

use App\Models\MajorCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MajorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // --- 運営 ---
            '運営・お知らせ',

            // --- 大学生活の核心 ---
            '授業・履修',
            '就活・進路',
            '悩み・相談',

            // --- つながり ---
            'サークル・交流',
            '地域・地元',

            // --- 学問 ---
            '文系学問',
            '理系学問',

            // --- ライフスタイル ---
            '暮らし・生活',
            'グルメ・料理',
            'ファッション・美容',

            // --- 趣味・カルチャー ---
            'エンタメ・ゲーム',
            '趣味・スポーツ',
            '乗り物・交通',
            'PC・デジタル',

            // --- その他 ---
            'ニュース・情勢',
            '雑談・その他',
        ];

        foreach ($categories as $category_name) {
            MajorCategory::firstOrCreate(['name' => $category_name]);
        }
    }
}

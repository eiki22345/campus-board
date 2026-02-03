<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Board;
use App\Models\MajorCategory;

class CommonBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 1. Boardモデルに書いたリストを取得
        $contents = Board::common_board_contents();

        // 2. カテゴリIDを検索用にあらかじめ取得（名前 => ID の配列にする）
        $categories = MajorCategory::pluck('id', 'name');

        foreach ($contents as $content) {
            $category_name = $content['category'];

            // もしMajorCategorySeederで作り忘れたカテゴリがあったらスキップ（エラー防止）
            if (!isset($categories[$category_name])) {
                continue;
            }

            // 3. 共通板を作成 (university_id を null にして保存)
            Board::firstOrCreate(
                [
                    'name' => $content['name'],
                    'university_id' => null, // ★ここが共通板の証
                ],
                [
                    'major_category_id' => $categories[$category_name],
                ]
            );
        }
    }
}

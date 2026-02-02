<?php

namespace App\Console\Commands;

use App\Models\MajorCategory;
use App\Models\University;
use App\Models\Board;
use Illuminate\Console\Command;


class Sync_University_Boards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'board_university_sync';

    protected $description = '全体学に対して、デフォルト板を完全同期(追加・削除）する。';

    public function handle()
    {
        // ユーザーに確認を促す
        // カテゴリ不一致の修正＝削除＆再作成になるため、念のため警告文を強化しています
        if (!$this->confirm('デフォルト設定にない板、およびカテゴリ設定が異なる板は削除（再作成）されます。これに伴いスレッドも削除される可能性があります。実行してよろしいですか？')) {
            $this->info('キャンセルしました。');
            return;
        }

        $this->info('板の同期を開始します...');

        $board_contents = University::default_board_contents();
        $universities = University::all();

        // 高速化: カテゴリ名 => ID の対応表を先に作っておきます
        $categories = MajorCategory::all()->pluck('id', 'name');

        foreach ($universities as $university) {
            $this->comment("チェック中: {$university->name}");

            // --- 1. この大学にあるべき「正しい板のリスト」を作成 ---
            // キー: 板の名前, 値: 正しいカテゴリID
            $correct_boards = [];

            foreach ($board_contents as $content) {
                $cat_name = $content['category'];

                // カテゴリが存在しない場合はエラー表示してスキップ
                if (!isset($categories[$cat_name])) {
                    $this->error("カテゴリ定義が見つかりません: {$cat_name}");
                    continue;
                }

                $full_name = $university->name . $content['suffix'];
                $correct_boards[$full_name] = $categories[$cat_name];
            }

            // --- 2. 削除フェーズ (Sync) ---
            // 先に掃除をします。これで「名前はあるけどカテゴリが違う」板も消えます。
            $existing_boards = Board::where('university_id', $university->id)->get();

            foreach ($existing_boards as $existing_board) {
                // A. 名前がリストにない -> 削除
                if (!array_key_exists($existing_board->name, $correct_boards)) {
                    $existing_board->delete();
                    $this->warn(" [削除] {$existing_board->name} (定義なし)");
                    continue;
                }

                // B. 名前はあるが、カテゴリIDが違う -> 削除
                $correct_cat_id = $correct_boards[$existing_board->name];
                if ($existing_board->major_category_id !== $correct_cat_id) {
                    $existing_board->delete();
                    $this->warn(" [削除] {$existing_board->name} (カテゴリ不一致のため再作成: {$existing_board->major_category_id} -> {$correct_cat_id})");
                }
            }

            // --- 3. 作成フェーズ ---
            // 削除フェーズで「カテゴリ違い」は消えているので、ここで正しく作り直されます
            foreach ($correct_boards as $name => $category_id) {
                $board = Board::firstOrCreate(
                    [
                        'name' => $name,
                        'university_id' => $university->id,
                    ],
                    [
                        'major_category_id' => $category_id,
                    ]
                );

                if ($board->wasRecentlyCreated) {
                    $this->info(" [作成] {$name}");
                }
            }
        }

        $this->info('同期完了! すべての大学の板が定義通りになりました。');
    }
}

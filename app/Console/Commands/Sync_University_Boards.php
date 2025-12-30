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

    protected $description = '全体学に対して、足りないデフォルト板を追加する。';

    public function handle()
    {
        $this->info('板の動機を開始します...');

        $board_contents = University::default_board_contents();

        $universities = University::all();

        foreach ($universities as $university) {
            $this->comment("チェック中:{$university->name}");

            foreach ($board_contents as $board_content) {

                $major_category = MajorCategory::where('name', $board_content['category'])->first();

                if (!$major_category) continue;

                $board_name = $university->name . $board_content['suffix'];

                $board = Board::firstOrCreate(
                    [
                        'name' => $board_name,
                        'university_id' => $university->id,
                    ],
                    [
                        'major_category_id' => $major_category->id,
                    ]
                );

                if ($board->wasRecentlyCreated) {
                    $this->info(" [作成] {$board_name}");
                }
            }
        }
        $this->info('同期完了!すべての大学に最新の板が反映されました。');
    }
}

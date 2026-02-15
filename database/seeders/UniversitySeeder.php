<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;
use SplFileObject;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('csv/universities.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSVファイルが見つかりません: $csvPath");
            $this->command->warn("databaseフォルダの中に csv フォルダを作成し、その中に universities.csv を置いてください。");
            return;
        }

        $file = new SplFileObject($csvPath);
        $file->setFlags(
            SplFileObject::READ_CSV |
                SplFileObject::READ_AHEAD |
                SplFileObject::SKIP_EMPTY |
                SplFileObject::DROP_NEW_LINE
        );

        $count = 0;
        $this->command->info("大学データの登録を開始します（板の自動生成を含むため少し時間がかかります）...");

        foreach ($file as $line) {
            if (count($line) < 3) continue;

            $name = trim($line[0]);
            $domain = trim($line[1]);
            $regionId = (int)trim($line[2]);


            University::firstOrCreate(
                ['email_domain' => $domain],
                [
                    'name' => $name,
                    'region_id' => $regionId,
                ]
            );

            $count++;

            if ($count % 50 === 0) {
                $this->command->getOutput()->write('.');
            }
        }

        $this->command->newLine();
        $this->command->info("完了！ {$count} 件の大学データを登録しました。");
    }
}

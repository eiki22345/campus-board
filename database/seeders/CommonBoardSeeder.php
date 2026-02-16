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


        $contents = Board::common_board_contents();


        $categories = MajorCategory::pluck('id', 'name');

        foreach ($contents as $content) {
            $category_name = $content['category'];


            if (!isset($categories[$category_name])) {
                continue;
            }

            Board::firstOrCreate(
                [
                    'name' => $content['name'],
                    'university_id' => null,
                ],
                [
                    'major_category_id' => $categories[$category_name],
                ]
            );
        }
    }
}

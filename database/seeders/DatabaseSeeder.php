<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\University;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MajorCategorySeeder::class,
            CommonBoardSeeder::class,
            RegionSeeder::class,
            UniversitySeeder::class,

        ]);

        $target_univ_name = '北海学園大学';
        $admin_university = University::where('name', $target_univ_name)->first();

        // 万が一、大学が見つからない場合は最初の大学を割り当てる（エラー回避）
        if (!$admin_university) {
            $admin_university = University::first();
        }

        // ユーザー作成
        if ($admin_university) {
            User::create([
                'nickname'      => 'nuts',
                'email'         => '3024123e@hgu.jp',
                'password'      => Hash::make('Pukutyan@M12'),
                'university_id' => $admin_university->id,
                'role'          => 1,
            ]);
        }
    }
}

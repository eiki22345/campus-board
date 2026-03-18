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
            NgWordSeeder::class,

        ]);

        $target_univ_name = '北海学園大学';
        $admin_university = University::where('name', $target_univ_name)->first();


        if (!$admin_university) {
            $admin_university = University::first();
        }


        if ($admin_university) {
            User::create([
                'nickname'      => env('ADMIN_NICKNAME', 'admin'),
                'email'         => env('ADMIN_EMAIL'),
                'password'      => Hash::make(env('ADMIN_PASSWORD')),
                'university_id' => $admin_university->id,
                'role'          => 1,
            ]);
        }
    }
}

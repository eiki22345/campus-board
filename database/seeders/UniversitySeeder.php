<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\University;
use Illuminate\Database\Seeder;



class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $hokkaido = Region::where('name', '北海道')->first()->id;
        $tohoku   = Region::where('name', '東北')->first()->id;
        $kanto    = Region::where('name', '関東')->first()->id;
        $chubu    = Region::where('name', '中部')->first()->id;
        $kinki    = Region::where('name', '近畿')->first()->id;
        $chugoku  = Region::where('name', '中国')->first()->id;
        $shikoku  = Region::where('name', '四国')->first()->id;
        $kyushu   = Region::where('name', '九州')->first()->id;

        $universities = [
            // 北海道
            ['name' => '北海学園大学', 'email_domain' => 'hgu.jp', 'region_id' => $hokkaido],
            ['name' => '北海道大学', 'email_domain' => 'hokudai.ac.jp', 'region_id' => $hokkaido],


            // 東北


            // 関東

        ];

        foreach ($universities as $university) {
            University::firstOrCreate(
                ['name' => $university['name']],
                $university
            );
        }
    }
}

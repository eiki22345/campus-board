<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            ['name' => '北海道', 'sort_order' => 1],
            ['name' => '東北',   'sort_order' => 2],
            ['name' => '関東',   'sort_order' => 3],
            ['name' => '中部',   'sort_order' => 4],
            ['name' => '近畿',   'sort_order' => 5],
            ['name' => '中国',   'sort_order' => 6],
            ['name' => '四国',   'sort_order' => 7],
            ['name' => '九州',   'sort_order' => 8],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}

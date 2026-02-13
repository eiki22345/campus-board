<?php

namespace Database\Seeders;

use App\Models\NgWord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class NgWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configWords = config('ng_words.list', []);

        foreach ($configWords as $word) {
            if (!empty($word)) {
                NgWord::firstOrCreate(['word' => $word]);
            }
        }
    }
}

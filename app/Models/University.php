<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MajorCategory;
use App\Models\Board;

class University extends Model
{

    protected $fillable = ['name', 'email_domain', 'region_id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public static function default_board_contents()
    {
        return [
            // --- 1. 授業・履修  ---
            ['category' => '授業・履修', 'suffix' => '/楽単・鬼単情報'],
            ['category' => '授業・履修', 'suffix' => '/テスト・試験対策'],
            ['category' => '授業・履修', 'suffix' => '/ゼミ・研究室選び'],
            ['category' => '授業・履修', 'suffix' => '/文系学部'],
            ['category' => '授業・履修', 'suffix' => '/理系学部'],
            ['category' => '授業・履修', 'suffix' => '/医療系学部'],
            ['category' => '授業・履修', 'suffix' => '/資格・留学'],
            ['category' => '授業・履修', 'suffix' => '/教授・教員の話'],

            // --- 2. 暮らし・バイト (生活系) ---
            ['category' => '暮らし・生活', 'suffix' => '/新入生・質問'],
            ['category' => '暮らし・生活', 'suffix' => '/学食・周辺グルメ'],
            ['category' => '暮らし・生活', 'suffix' => '/一人暮らし・住まい'],
            ['category' => '暮らし・生活', 'suffix' => '/自炊・料理・レシピ'],
            ['category' => '暮らし・生活', 'suffix' => '/ファッション'],
            ['category' => '暮らし・生活', 'suffix' => '/雪・天気・交通'],
            ['category' => '暮らし・生活', 'suffix' => '/古本・教科書譲渡'],
            ['category' => '暮らし・生活', 'suffix' => '/落とし物・困りごと'],

            // --- 3. サークル・遊び (交流系) ---
            ['category' => 'サークル・交流', 'suffix' => '/サークル・部活(体)'],
            ['category' => 'サークル・交流', 'suffix' => '/サークル・部活(文)'],
            ['category' => 'サークル・交流', 'suffix' => '/イベント・学祭'],
            ['category' => 'サークル・交流', 'suffix' => '/PC・スマホ・ガジェット'],
            ['category' => 'サークル・交流', 'suffix' => '/ゲーム・趣味'],
            ['category' => 'サークル・交流', 'suffix' => '/オタ活'],
            ['category' => 'サークル・交流', 'suffix' => '/恋愛相談'],

            // --- 4. 就活・将来 (キャリア系) ---
            ['category' => '就活・進路', 'suffix' => '/進路・キャリア相談'],
            ['category' => '就活・進路', 'suffix' => '/就活・インターン'],
            ['category' => '就活・進路', 'suffix' => '/就活・選考・ES'],
            ['category' => '就活・進路', 'suffix' => '/院試・進学'],
            ['category' => '就活・進路', 'suffix' => '/公務員・教員採用'],

            // --- 5. 雑談・その他 (ガス抜き系) ---
            ['category' => '雑談・その他', 'suffix' => '/大学雑談・ニュース'],
            ['category' => '雑談・その他', 'suffix' => '/なんでも雑談'],
            ['category' => '雑談・その他', 'suffix' => '/なんでも実況'],
            ['category' => '雑談・その他', 'suffix' => '/仮面浪人・編入・留年'],
            ['category' => '雑談・その他', 'suffix' => '/愚痴・吐き出し'],
        ];
    }

    protected static function booted()
    {
        static::created(function ($university) {

            $board_contents = self::default_board_contents();

            foreach ($board_contents as $board_content) {

                $Major_category = MajorCategory::where('name', $board_content['category'])->first();

                if ($Major_category) {
                    Board::create([
                        'name' => $university->name . $board_content['suffix'],
                        'major_category_id' => $Major_category->id,
                        'university_id' => $university->id,
                    ]);
                }
            }
        });
    }
}

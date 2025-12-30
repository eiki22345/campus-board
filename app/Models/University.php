<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MajorCategory;
use App\Models\Board;

class University extends Model
{

    protected $fillable = ['name', 'email_domain'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public static function default_board_contents()
    {

        return [
            ['suffix' => '/講義・履修', 'category' => '大学'],
            ['suffix' => '/サークル・イベント', 'category' => '大学'],
            ['suffix' => '/学内雑談', 'category' => '大学'],
            ['suffix' => '/落とし物・困りごと', 'category' => '大学'],
            ['suffix' => '/就活', 'category' => '大学'],
            ['suffix' => '/恋愛・相談', 'category' => '大学'],
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

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\YahooTextAnalysisService;

class NoInappropriateWords implements ValidationRule
{
    protected $analyzer;

    public function __construct()
    {
        $this->analyzer = new YahooTextAnalysisService();
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ng_words = config('ng_words.list', []);
        $tokens = $this->analyzer->get_tokens((string) $value);


        foreach ($tokens as $token) {
            if (in_array($token, $ng_words)) {
                $fail('投稿内容に不適切な表現（' . $token . '）が含まれています。');
                return;
            }
        }


        foreach ($ng_words as $ng) {
            if (mb_strpos($value, $ng) !== false) {
                $fail('投稿内容に不適切な表現（' . $ng . '）が含まれています。');
                return;
            }
        }
    }
}

<?php

namespace App\Http\Requests;

use App\Models\NgWord;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => [
                'required',
                'string',
                'max:8',
                function (string $attribute, mixed $value, Closure $fail) {
                    $ngWords = NgWord::pluck('word')->toArray();
                    if (in_array($value, $ngWords)) {
                        $fail('不適切なニックネームは使用できません。');
                    }
                },
            ],
        ];
    }
}

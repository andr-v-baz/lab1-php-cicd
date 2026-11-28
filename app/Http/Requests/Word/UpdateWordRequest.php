<?php

namespace App\Http\Requests\Word;

use App\Rules\DictionaryAlphabet;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'original' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('en'),
            ],
            'translation' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('uk'),
            ],
            'language_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:languages,id',
            ],
            'translations' => [
                'sometimes',
                'array',
            ],
            'translations.*' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('uk'),
            ],
        ];
    }
}

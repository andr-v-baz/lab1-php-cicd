<?php

namespace App\Http\Requests\Word;

use App\Rules\DictionaryAlphabet;
use Illuminate\Foundation\Http\FormRequest;

class StoreWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'original' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('en'),
            ],
            'translation' => [
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
        ];
    }
}

<?php

namespace App\Services;

class DictionaryService
{
    private array $dictionary = [
        'apple' => 'яблуко',
        'book' => 'книга',
        'cat' => 'кіт',
        'dog' => 'собака',
        'house' => 'будинок',
        'water' => 'вода',
        'sun' => 'сонце',
    ];

    public function translate(string $word): ?string
    {
        $word = strtolower(trim($word));

        return $this->dictionary[$word] ?? null;
    }
}

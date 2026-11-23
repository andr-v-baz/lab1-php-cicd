<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use App\Models\Word;
use Illuminate\Database\Seeder;

class DictionarySeeder extends Seeder
{
    public function run(): void
    {
        $english = Language::firstOrCreate(
            ['code' => 'en'],
            ['name' => 'English']
        );

        $ukrainian = Language::firstOrCreate(
            ['code' => 'uk'],
            ['name' => 'Ukrainian']
        );

        $words = [
            ['hello', 'привіт'],
            ['book', 'книга'],
            ['cat', 'кіт'],
            ['house', 'будинок'],
            ['water', 'вода'],
        ];

        foreach ($words as [$original, $translated]) {
            $word = Word::firstOrCreate(
                [
                    'original' => $original,
                    'language_id' => $english->id,
                ],
                ['translation' => $translated]
            );

            Translation::firstOrCreate(
                [
                    'word_id' => $word->id,
                    'language_id' => $ukrainian->id,
                ],
                ['translated_text' => $translated]
            );
        }
    }
}

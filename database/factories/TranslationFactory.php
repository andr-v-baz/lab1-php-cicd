<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Translation;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'word_id' => Word::factory(),
            'language_id' => Language::factory(),
            'translated_text' => fake()->word(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Word>
 */
class WordFactory extends Factory
{
    protected $model = Word::class;

    public function definition(): array
    {
        return [
            'original' => fake()->unique()->word(),
            'translation' => fake()->word(),
            'language_id' => Language::factory(),
        ];
    }
}

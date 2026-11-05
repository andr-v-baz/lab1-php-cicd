<?php

namespace App\Services;

use App\Models\Word;
use Faker\Factory;

class WordGenerator
{
    public function generate(int $count = 5): array
    {
        $faker = Factory::create();
        $words = [];

        for ($i = 0; $i < $count; $i++) {
            $words[] = new Word(
                $faker->unique()->word(),
                $faker->word(),
                'en'
            );
        }

        return $words;
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

class DataProcessor
{
    // Завдання 1: знайти найменшу цифру натурального числа
    public function findMinDigit(int $number): int
    {
        if ($number <= 0) {
            throw new \InvalidArgumentException('Число має бути натуральним');
        }

        return min(array_map('intval', str_split((string) $number)));
    }

    // Завдання 2: поміняти місцями перше та останнє слова
    public function swapFirstAndLastWords(string $text): string
    {
        $words = preg_split('/\s+/u', trim($text));

        if (count($words) < 2) {
            return $text;
        }

        $lastIndex = count($words) - 1;

        [$words[0], $words[$lastIndex]] = [$words[$lastIndex], $words[0]];

        return implode(' ', $words);
    }

    // Завдання 3: перевірити, чи малі латинські літери
    // розташовані за зростанням
    public function areLettersAscending(string $text): bool
    {
        preg_match_all('/[a-z]/', $text, $matches);

        $letters = $matches[0];

        for ($i = 1; $i < count($letters); $i++) {
            if ($letters[$i] < $letters[$i - 1]) {
                return false;
            }
        }

        return true;
    }
}

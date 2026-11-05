<?php

require __DIR__.'/vendor/autoload.php';

use App\Services\DataProcessor;
use App\Services\DictionaryService;
use App\Services\WordGenerator;

echo "=== ЕЛЕКТРОННИЙ СЛОВНИК ===\n";

$dictionary = new DictionaryService;

foreach (['apple', 'book', 'cat', 'unknown'] as $word) {
    $translation = $dictionary->translate($word);

    echo $word.' -> '.($translation ?? 'Переклад не знайдено')."\n";
}

echo "\n=== ГЕНЕРАЦІЯ СЛІВ (FAKER) ===\n";

$generator = new WordGenerator;

foreach ($generator->generate(5) as $word) {
    echo $word->getInfo()."\n";
}

echo "\n=== ЗАВДАННЯ ВАРІАНТА №2 ===\n";

$processor = new DataProcessor;

echo 'Найменша цифра числа 58321: '
    .$processor->findMinDigit(58321)."\n";

echo 'Перестановка першого й останнього слів: '
    .$processor->swapFirstAndLastWords('PHP is very interesting')."\n";

echo 'Літери abc у порядку зростання: '
    .($processor->areLettersAscending('abc') ? 'Так' : 'Ні')."\n";

echo 'Літери cba у порядку зростання: '
    .($processor->areLettersAscending('cba') ? 'Так' : 'Ні')."\n";

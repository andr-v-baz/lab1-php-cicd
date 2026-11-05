<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use App\Services\DataProcessor;
use App\Services\DictionaryService;
use App\Services\EntityService;
use Faker\Factory;

$dictionary = new DictionaryService;
$service = new EntityService;
$processor = new DataProcessor;

$faker = Factory::create();

$wordLengths = [];

for ($i = 0; $i < 10; $i++) {
    $wordLengths[] = $faker->numberBetween(2, 15);
}

$minLength = 7;

$filteredLengths = array_filter(
    $wordLengths,
    function (int $length) use ($minLength): bool {
        return $length >= $minLength;
    }
);

try {
    $service->applyDiscount(100, 150);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
} finally {
    $finalMessage = 'Спробу розрахунку завершено.';
}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Електронний словник</title>
</head>
<body>

<h1>Електронний словник</h1>

<h2>Переклад слів</h2>
<ul>
    <?php foreach (['apple', 'book', 'cat'] as $word) { ?>
        <li>
            <?= htmlspecialchars($service->formatTranslation(
                $word,
                $dictionary->translate($word) ?? 'Не знайдено'
            ), ENT_QUOTES, 'UTF-8') ?>
        </li>
    <?php } ?>
</ul>

<h2>Генерація даних Faker</h2>

<p>Випадкові довжини слів:</p>
<p><?= implode(', ', $wordLengths) ?></p>

<p>Довжини слів від <?= $minLength ?> символів:</p>
<p><?= implode(', ', $filteredLengths) ?></p>

<h2>Обробка винятків</h2>
<p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<p><?= htmlspecialchars($finalMessage, ENT_QUOTES, 'UTF-8') ?></p>

<h2>Завдання варіанта №2</h2>

<p>
    Мінімальна цифра числа 58321:
    <?= $processor->findMinDigit(58321) ?>
</p>

<p>
    Перестановка слів:
    <?= htmlspecialchars(
        $processor->swapFirstAndLastWords('PHP is very interesting'),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    Літери abc за зростанням:
    <?= $processor->areLettersAscending('abc') ? 'Так' : 'Ні' ?>
</p>

<p>
    Літери cba за зростанням:
    <?= $processor->areLettersAscending('cba') ? 'Так' : 'Ні' ?>
</p>

</body>
</html>

<?php

declare(strict_types=1);

$words = ['apple', 'book', 'cat', 'house', 'water'];

sort($words);

$word = [
    'id' => 1,
    'text' => 'apple',
    'languageCode' => 'en',
    'partOfSpeech' => 'noun',
    'createdAt' => '2026-11-05',
];

?>

<h2>Список слів електронного словника</h2>

<ul>
    <?php foreach ($words as $item) { ?>
        <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
    <?php } ?>
</ul>

<h2>Інформація про слово</h2>

<ul>
    <?php foreach ($word as $key => $value) { ?>
        <li>
            <strong><?= htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8') ?>:</strong>
            <?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>
        </li>
    <?php } ?>
</ul>
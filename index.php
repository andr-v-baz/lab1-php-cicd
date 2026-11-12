<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use App\Core\Database;
use App\Core\EntityFactory;
use App\Interfaces\ExportableInterface;
use App\Services\ConsoleLogger;
use App\Services\DataProcessor;
use App\Services\DictionaryService;
use App\Services\EntityService;
use App\Tasks\Artist;
use App\Tasks\Genre;
use App\Tasks\HeadOfDepartment;
use App\Tasks\Student;
use App\Tasks\Teacher;
use App\Tasks\Triangle;
use Faker\Factory;

$dictionary = new DictionaryService;
$logger = new ConsoleLogger;
$service = new EntityService($logger);
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

ob_start();

$database1 = Database::getInstance();
$database2 = Database::getInstance();

$databaseMessage = ob_get_clean();

$isSameDatabase = $database1 === $database2;

$entityFactory = new EntityFactory;

$dictionaryWord = $entityFactory->createEntity('word', [
    'id' => '1',
    'createdAt' => date('Y-m-d H:i:s'),
    'original' => 'apple',
    'translation' => 'яблуко',
]);

$dictionaryCategory = $entityFactory->createEntity('category', [
    'id' => '2',
    'createdAt' => date('Y-m-d H:i:s'),
    'name' => 'Фрукти',
    'description' => 'Слова, пов’язані з фруктами',
]);

$jsonExport = $dictionaryWord instanceof ExportableInterface
    ? $dictionaryWord->exportToJson()
    : '';

$genre = new Genre(1, 'Попмузика');

$artist1 = new Artist(1, 'Океан Ельзи', true, 1994);
$artist2 = new Artist(2, 'Тіна Кароль', false, 2005);

$genre->addArtist($artist1);
$genre->addArtist($artist2);

$foundArtist = $genre->findArtist(1);

$genre->removeArtist(2);

$student = new Student('Анна', 20, 'КН-41');

$teacher = new Teacher('Олена', 40, 'КН-41', 'PHP');

$head = new HeadOfDepartment(
    'Іван',
    50,
    'КН-41',
    'Програмування',
    'Комп’ютерні науки'
);

$triangle = new Triangle(3, 4, 5);

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
            <?= htmlspecialchars(
                $service->formatTranslation(
                    $word,
                    $dictionary->translate($word) ?? 'Не знайдено'
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
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

<h2>Лабораторна №4 — ООП та патерни проєктування</h2>

<h3>1. Singleton</h3>

<p>
    <?= htmlspecialchars(
        $databaseMessage,
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    Обидва виклики повернули один об'єкт:
    <?= $isSameDatabase ? 'Так' : 'Ні' ?>
</p>

<h3>2. Наслідування та Factory</h3>

<p>
    Слово:
    <?= htmlspecialchars(
        $dictionaryWord->getDetails(),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    Категорія:
    <?= htmlspecialchars(
        $dictionaryCategory->getDetails(),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<h3>3. Інтерфейс і трейт — JSON</h3>

<pre><?= htmlspecialchars(
    $jsonExport,
    ENT_QUOTES,
    'UTF-8'
) ?></pre>

<h3>4. Dependency Injection</h3>

<p>
    <?= htmlspecialchars(
        $service->formatTranslation('hello', 'привіт'),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    Знижка 20% від 100:
    <?= $service->applyDiscount(100, 20) ?>
</p>

<h3>5. Індивідуальне завдання — Artist і Genre</h3>

<p>
    Жанр:
    <?= htmlspecialchars(
        $genre->name,
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>Знайдений виконавець:</p>

<p>
    <?= htmlspecialchars(
        $foundArtist?->getDetails() ?? 'Не знайдено',
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>Виконавці після видалення:</p>

<ul>
    <?php foreach ($genre->getArtists() as $artist) { ?>
        <li>
            <?= htmlspecialchars(
                $artist->getDetails(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </li>
    <?php } ?>
</ul>

<h3>6. Наслідування класів</h3>

<p>
    <?= htmlspecialchars(
        $student->getRoleDetails(),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    <?= htmlspecialchars(
        $teacher->getRoleDetails(),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    <?= htmlspecialchars(
        $head->getRoleDetails(),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<h3>7. Трикутник — формула Герона</h3>

<p>Сторони трикутника: 3, 4, 5</p>

<p>
    Периметр:
    <?= $triangle->getPerimeter() ?>
</p>

<p>
    Площа:
    <?= $triangle->getArea() ?>
</p>

</body>
</html>


# Лабораторна робота №1

## Керування версіями та налаштування CI/CD для PHP проєкту

[![PHP CI Application](https://github.com/andr-v-baz/lab1-php-cicd/actions/workflows/php-ci.yml/badge.svg?branch=main)](https://github.com/andr-v-baz/lab1-php-cicd/actions/workflows/php-ci.yml)

## Опис проєкту

Навчальний PHP-проєкт для вивчення Git, GitHub та автоматизації CI/CD за допомогою GitHub Actions.

## Функціонал

- Додавання двох чисел.
- Віднімання двох чисел.
- Автоматичне тестування за допомогою PHPUnit.
- Перевірка форматування PHP-коду за допомогою Laravel Pint.
- Автоматичний запуск CI-пайплайну через GitHub Actions.
- Використання GitHub Secrets.

## Запуск тестів

Встановлення залежностей:

```bash
composer install
```

Запуск модульних тестів:

```bash
./vendor/bin/phpunit tests/Unit
```

Перевірка форматування:

```bash
./vendor/bin/pint --test
```

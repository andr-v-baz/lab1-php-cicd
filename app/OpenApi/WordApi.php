<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/words',
    summary: 'Отримати список слів',
    tags: ['Words'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Список слів із перекладами'
        ),
    ]
)]
#[OA\Post(
    path: '/words',
    summary: 'Додати нове слово',
    tags: ['Words'],
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['original', 'translation', 'language_id'],
            properties: [
                new OA\Property(
                    property: 'original',
                    type: 'string',
                    example: 'apple'
                ),
                new OA\Property(
                    property: 'translation',
                    type: 'string',
                    example: 'яблуко'
                ),
                new OA\Property(
                    property: 'language_id',
                    type: 'integer',
                    example: 1
                ),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Слово створено'),
        new OA\Response(response: 401, description: 'Не авторизовано'),
        new OA\Response(response: 422, description: 'Помилка валідації'),
    ]
)]
#[OA\Get(
    path: '/words/{word}',
    summary: 'Переглянути слово',
    tags: ['Words'],
    parameters: [
        new OA\Parameter(
            name: 'word',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Дані слова'),
        new OA\Response(response: 404, description: 'Слово не знайдено'),
    ]
)]
#[OA\Put(
    path: '/words/{word}',
    summary: 'Оновити своє слово',
    tags: ['Words'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(
            name: 'word',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'original',
                    type: 'string',
                    example: 'flower'
                ),
                new OA\Property(
                    property: 'translation',
                    type: 'string',
                    example: 'квітка'
                ),
                new OA\Property(
                    property: 'language_id',
                    type: 'integer',
                    example: 1
                ),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Слово оновлено'),
        new OA\Response(response: 401, description: 'Не авторизовано'),
        new OA\Response(response: 403, description: 'Немає прав на редагування'),
        new OA\Response(response: 404, description: 'Слово не знайдено'),
    ]
)]
#[OA\Delete(
    path: '/words/{word}',
    summary: 'Видалити своє слово',
    tags: ['Words'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(
            name: 'word',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Слово видалено'),
        new OA\Response(response: 401, description: 'Не авторизовано'),
        new OA\Response(response: 403, description: 'Немає прав на видалення'),
        new OA\Response(response: 404, description: 'Слово не знайдено'),
    ]
)]
class WordApi
{
}

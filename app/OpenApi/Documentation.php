<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'English-Ukrainian Dictionary API',
    description: 'REST API англо-українського словника з авторизацією Laravel Sanctum.'
)]
#[OA\Server(
    url: '/api/v1',
    description: 'API версії 1'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Введіть токен, отриманий після входу через /login.'
)]
class Documentation {}

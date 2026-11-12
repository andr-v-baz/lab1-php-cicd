<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\AbstractEntity;
use App\Models\DictionaryCategory;
use App\Models\DictionaryWord;
use InvalidArgumentException;

class EntityFactory
{
    public function createEntity(string $type, array $data): AbstractEntity
    {
        return match ($type) {
            'word' => new DictionaryWord(
                $data['id'],
                $data['createdAt'],
                $data['original'],
                $data['translation']
            ),

            'category' => new DictionaryCategory(
                $data['id'],
                $data['createdAt'],
                $data['name'],
                $data['description']
            ),

            default => throw new InvalidArgumentException(
                'Невідомий тип сутності: '.$type
            ),
        };
    }
}

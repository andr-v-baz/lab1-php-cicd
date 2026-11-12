<?php

declare(strict_types=1);

namespace App\Models;

class DictionaryCategory extends AbstractEntity
{
    public function __construct(
        string $id,
        string $createdAt,
        private string $name,
        private string $description
    ) {
        parent::__construct($id, $createdAt);
    }

    public function getDetails(): string
    {
        return $this->name.' — '.$this->description;
    }
}

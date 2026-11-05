<?php

namespace App\Models;

class Word
{
    public function __construct(
        public string $original,
        public string $translation,
        public string $language
    ) {}

    public function getInfo(): string
    {
        return "{$this->original} → {$this->translation} ({$this->language})";
    }
}

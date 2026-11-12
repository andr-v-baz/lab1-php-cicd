<?php

declare(strict_types=1);

namespace App\Tasks;

class Artist
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $isBand,
        public int $startYear
    ) {}

    public function getDetails(): string
    {
        return $this->name
            .' ('.($this->isBand ? 'гурт' : 'виконавець')
            .', початок діяльності: '.$this->startYear.')';
    }
}

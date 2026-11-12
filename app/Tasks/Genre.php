<?php

declare(strict_types=1);

namespace App\Tasks;

class Genre
{
    private array $artists = [];

    public function __construct(
        public int $genre_id,
        public string $name
    ) {}

    public function addArtist(Artist $artist): void
    {
        $this->artists[$artist->id] = $artist;
    }

    public function removeArtist(int $id): void
    {
        unset($this->artists[$id]);
    }

    public function findArtist(int $id): ?Artist
    {
        return $this->artists[$id] ?? null;
    }

    public function getArtists(): array
    {
        return array_values($this->artists);
    }
}

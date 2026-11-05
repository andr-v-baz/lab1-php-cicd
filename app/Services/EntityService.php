<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class EntityService
{
    public function formatTranslation(string $word, string $translation): string
    {
        return $word.' → '.$translation;
    }

    public function applyDiscount(float $price, float $discount): float
    {
        if ($discount < 0 || $discount > 100) {
            throw new Exception('Неприпустиме значення знижки!');
        }

        return $price - ($price * $discount / 100);
    }
}

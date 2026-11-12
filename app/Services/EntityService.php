<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\LoggerInterface;
use Exception;

class EntityService
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function formatTranslation(string $word, string $translation): string
    {
        $this->logger->log('Форматування перекладу слова: '.$word);

        return $word.' → '.$translation;
    }

    public function applyDiscount(float $price, float $discount): float
    {
        if ($discount < 0 || $discount > 100) {
            throw new Exception('Неприпустиме значення знижки!');
        }

        $this->logger->log('Розрахунок знижки: '.$discount.'%');

        return $price - ($price * $discount / 100);
    }
}

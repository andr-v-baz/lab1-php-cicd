<?php

declare(strict_types=1);

namespace App\Traits;

trait TimestampTrait
{
    public function getCurrentTimestamp(): string
    {
        return date('Y-m-d H:i:s');
    }
}

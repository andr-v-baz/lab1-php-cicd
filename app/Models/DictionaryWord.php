<?php

declare(strict_types=1);

namespace App\Models;

use App\Interfaces\ExportableInterface;
use App\Interfaces\TranslatableInterface;
use App\Traits\JsonExportTrait;
use App\Traits\TimestampTrait;

class DictionaryWord extends AbstractEntity implements ExportableInterface, TranslatableInterface
{
    use JsonExportTrait;
    use TimestampTrait;

    public function __construct(
        string $id,
        string $createdAt,
        private string $original,
        private string $translation
    ) {
        parent::__construct($id, $createdAt);
    }

    public function getDetails(): string
    {
        return $this->original.' — '.$this->translation;
    }

    public function translate(): string
    {
        return $this->translation;
    }
}

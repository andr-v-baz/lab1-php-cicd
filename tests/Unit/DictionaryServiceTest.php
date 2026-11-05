<?php

namespace Tests\Unit;

use App\Services\DictionaryService;
use PHPUnit\Framework\TestCase;

class DictionaryServiceTest extends TestCase
{
    public function test_translates_english_word(): void
    {
        $dictionary = new DictionaryService;

        $this->assertSame('яблуко', $dictionary->translate('apple'));
        $this->assertSame('книга', $dictionary->translate('book'));
    }

    public function test_ignores_uppercase_and_spaces(): void
    {
        $dictionary = new DictionaryService;

        $this->assertSame('яблуко', $dictionary->translate(' APPLE '));
    }

    public function test_returns_null_for_unknown_word(): void
    {
        $dictionary = new DictionaryService;

        $this->assertNull($dictionary->translate('unknown'));
    }
}

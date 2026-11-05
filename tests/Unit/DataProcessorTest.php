<?php

namespace Tests\Unit;

use App\Services\DataProcessor;
use PHPUnit\Framework\TestCase;

class DataProcessorTest extends TestCase
{
    public function test_find_min_digit(): void
    {
        $processor = new DataProcessor;

        $this->assertSame(1, $processor->findMinDigit(58321));
        $this->assertSame(0, $processor->findMinDigit(907));
    }

    public function test_swap_first_and_last_words(): void
    {
        $processor = new DataProcessor;

        $this->assertSame(
            'світ прекрасний Привіт',
            $processor->swapFirstAndLastWords('Привіт прекрасний світ')
        );
    }

    public function test_are_letters_ascending(): void
    {
        $processor = new DataProcessor;

        $this->assertTrue($processor->areLettersAscending('abc'));
        $this->assertFalse($processor->areLettersAscending('cba'));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

class DictionaryWordTest extends TestCase
{
    public function test_health_returns_200(): void
    {
        $this->getJson('/health')
            ->assertStatus(200)
            ->assertJsonPath('status', 'ok');
    }

    public function test_words_index_returns_200(): void
    {
        $this->getJson('/words')
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_word_show_returns_200(): void
    {
        $this->getJson('/words/1')
            ->assertStatus(200)
            ->assertJsonPath('word', 'hello');
    }

    public function test_missing_word_returns_404(): void
    {
        $this->getJson('/words/999')
            ->assertStatus(404);
    }

    public function test_store_without_header_returns_403(): void
    {
        $this->postJson('/words', [
            'word' => 'cat',
            'translation' => 'kit',
            'language' => 'en',
            'part_of_speech' => 'noun',
        ])
            ->assertStatus(403)
            ->assertHeader('X-Security-Policy', 'Enforced');
    }

    public function test_store_with_header_returns_201(): void
    {
        $this->withHeader('X-Dictionary-Client', 'lab5')
            ->postJson('/words', [
                'word' => 'cat',
                'translation' => 'kit',
                'language' => 'en',
                'part_of_speech' => 'noun',
            ])
            ->assertStatus(201)
            ->assertJsonPath('word', 'cat')
            ->assertHeader('X-Security-Policy', 'Enforced');
    }
}

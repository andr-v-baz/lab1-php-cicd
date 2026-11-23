<?php

namespace Tests\Feature;

use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WordWebTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Language::firstOrCreate(
            ['code' => 'en'],
            ['name' => 'English']
        );

        Language::firstOrCreate(
            ['code' => 'uk'],
            ['name' => 'Ukrainian']
        );
    }

    public function test_home_page_opens(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_words_page_opens(): void
    {
        $response = $this->get(route('words.index'));

        $response->assertStatus(200);
    }

    public function test_create_word_page_opens(): void
    {
        $response = $this->get(route('words.create'));

        $response->assertStatus(200);
    }

    public function test_languages_page_opens(): void
    {
        $response = $this->get(route('languages.index'));

        $response->assertStatus(200);
    }

    public function test_word_can_be_created(): void
    {
        $response = $this->post(route('words.store'), [
            'original' => 'apple',
            'translation' => 'яблуко',
        ]);

        $response->assertRedirect(route('words.index'));

        $this->assertDatabaseHas('words', [
            'original' => 'apple',
            'translation' => 'яблуко',
        ]);

        $this->assertDatabaseHas('translations', [
            'translated_text' => 'яблуко',
        ]);
    }

    public function test_invalid_word_shows_validation_errors(): void
    {
        $response = $this->from(route('words.create'))
            ->post(route('words.store'), [
                'original' => '12345',
                'translation' => '',
            ]);

        $response->assertRedirect(route('words.create'));

        $response->assertSessionHasErrors([
            'original',
            'translation',
        ]);

        $this->assertDatabaseMissing('words', [
            'original' => '12345',
        ]);
    }
}

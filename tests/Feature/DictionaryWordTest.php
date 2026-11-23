<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DictionaryWordTest extends TestCase
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

    public function test_health_returns_200(): void
    {
        $this->getJson('/health')
            ->assertStatus(200)
            ->assertJsonPath('status', 'ok');
    }

    public function test_words_index_returns_200(): void
    {
        $this->get(route('words.index'))
            ->assertStatus(200)
            ->assertViewIs('words.index');
    }

    public function test_word_show_returns_200(): void
    {
        $this->post(route('words.store'), [
            'original' => 'hello',
            'translation' => 'привіт',
        ])->assertRedirect(route('words.index'));

        $word = Word::where('original', 'hello')->firstOrFail();

        $this->get(route('words.show', $word->id))
            ->assertStatus(200)
            ->assertViewIs('words.show')
            ->assertSee('hello');
    }

    public function test_missing_word_returns_404(): void
    {
        $this->get(route('words.show', 999999))
            ->assertStatus(404);
    }

    public function test_store_without_original_returns_validation_error(): void
    {
        $this->from(route('words.create'))
            ->post(route('words.store'), [
                'original' => '',
                'translation' => 'кіт',
            ])
            ->assertRedirect(route('words.create'))
            ->assertSessionHasErrors('original');
    }

    public function test_store_with_valid_data_redirects_to_words_index(): void
    {
        $this->post(route('words.store'), [
            'original' => 'cat',
            'translation' => 'кіт',
        ])
            ->assertRedirect(route('words.index'));

        $this->assertDatabaseHas('words', [
            'original' => 'cat',
            'translation' => 'кіт',
        ]);

        $this->assertDatabaseHas('translations', [
            'translated_text' => 'кіт',
        ]);
    }
}

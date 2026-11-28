<?php

namespace Tests\Unit;

use App\Models\Language;
use App\Models\User;
use App\Services\WordService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WordServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_creates_word_and_translation(): void
    {
        Queue::fake();

        $english = Language::factory()->create([
            'code' => 'en',
        ]);

        Language::factory()->create([
            'code' => 'uk',
        ]);

        $user = User::factory()->create();

        $word = app(WordService::class)->create([
            'original' => 'hello',
            'translation' => 'привіт',
            'language_id' => $english->id,
        ], $user->id);

        $this->assertDatabaseHas('words', [
            'id' => $word->id,
            'original' => 'hello',
            'translation' => 'привіт',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('translations', [
            'word_id' => $word->id,
            'translated_text' => 'привіт',
        ]);
    }

    public function test_service_updates_word_and_translation(): void
    {
        Queue::fake();

        $english = Language::factory()->create([
            'code' => 'en',
        ]);

        Language::factory()->create([
            'code' => 'uk',
        ]);

        $user = User::factory()->create();

        $service = app(WordService::class);

        $word = $service->create([
            'original' => 'hello',
            'translation' => 'привіт',
            'language_id' => $english->id,
        ], $user->id);

        $service->update($word, [
            'original' => 'world',
            'translation' => 'світ',
        ]);

        $this->assertDatabaseHas('words', [
            'id' => $word->id,
            'original' => 'world',
            'translation' => 'світ',
        ]);

        $this->assertDatabaseHas('translations', [
            'word_id' => $word->id,
            'translated_text' => 'світ',
        ]);
    }

    public function test_service_deletes_word_and_translations(): void
    {
        Queue::fake();

        $english = Language::factory()->create([
            'code' => 'en',
        ]);

        Language::factory()->create([
            'code' => 'uk',
        ]);

        $user = User::factory()->create();

        $service = app(WordService::class);

        $word = $service->create([
            'original' => 'hello',
            'translation' => 'привіт',
            'language_id' => $english->id,
        ], $user->id);

        $wordId = $word->id;

        $service->delete($word);

        $this->assertDatabaseMissing('words', [
            'id' => $wordId,
        ]);

        $this->assertDatabaseMissing('translations', [
            'word_id' => $wordId,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Language;
use App\Models\SearchHistory;
use App\Models\Translation;
use App\Models\User;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DictionaryDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_language(): void
    {
        $language = Language::factory()->create();

        $this->assertDatabaseHas('languages', [
            'id' => $language->id,
            'code' => $language->code,
        ]);
    }

    public function test_word_belongs_to_language(): void
    {
        $word = Word::factory()->create();

        $this->assertInstanceOf(
            Language::class,
            $word->language
        );
    }

    public function test_translation_belongs_to_word(): void
    {
        $translation = Translation::factory()->create();

        $this->assertInstanceOf(
            Word::class,
            $translation->word
        );
    }

    public function test_language_has_many_words(): void
    {
        $language = Language::factory()->create();

        Word::factory()->count(3)->create([
            'language_id' => $language->id,
        ]);

        $this->assertCount(3, $language->words);
    }

    public function test_user_can_add_word_to_favorites(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'favorite@example.com',
            'password' => 'password123',
        ]);

        $word = Word::factory()->create();

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'word_id' => $word->id,
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'word_id' => $word->id,
        ]);

        $this->assertEquals(
            $word->id,
            $favorite->word->id
        );
    }

    public function test_user_search_history(): void
    {
        $user = User::create([
            'name' => 'Search User',
            'email' => 'search@example.com',
            'password' => 'password123',
        ]);

        SearchHistory::create([
            'user_id' => $user->id,
            'search_query' => 'hello',
        ]);

        $this->assertCount(
            1,
            $user->searchHistories
        );
    }
}

<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\User;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WordApiTest extends TestCase
{
    use RefreshDatabase;

    private function createLanguage(): Language
    {
        return Language::create([
            'name' => 'English',
            'code' => 'en',
        ]);
    }

    private function createWord(
        Language $language,
        ?User $owner = null
    ): Word {
        return Word::create([
            'original' => 'apple',
            'translation' => 'яблуко',
            'language_id' => $language->id,
            'user_id' => $owner?->id,
        ]);
    }

    public function test_anyone_can_get_words(): void
    {
        $language = $this->createLanguage();

        $this->createWord($language);

        $response = $this->getJson('/api/v1/words');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.original', 'apple')
            ->assertJsonPath('data.0.translation', 'яблуко');
    }

    public function test_guest_cannot_create_word(): void
    {
        $language = $this->createLanguage();

        $response = $this->postJson('/api/v1/words', [
            'original' => 'sun',
            'translation' => 'сонце',
            'language_id' => $language->id,
        ]);

        $response->assertUnauthorized();

        $this->assertDatabaseMissing('words', [
            'original' => 'sun',
        ]);
    }

    public function test_authenticated_user_can_create_word(): void
    {
        $language = $this->createLanguage();
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/words', [
            'original' => 'sun',
            'translation' => 'сонце',
            'language_id' => $language->id,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.original', 'sun')
            ->assertJsonPath('data.owner.id', $user->id);

        $this->assertDatabaseHas('words', [
            'original' => 'sun',
            'translation' => 'сонце',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_update_another_users_word(): void
    {
        $language = $this->createLanguage();

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $word = $this->createWord($language, $owner);

        Sanctum::actingAs($otherUser);

        $response = $this->putJson(
            "/api/v1/words/{$word->id}",
            [
                'translation' => 'інший переклад',
            ]
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('words', [
            'id' => $word->id,
            'translation' => 'яблуко',
        ]);
    }

    public function test_owner_can_update_own_word(): void
    {
        $language = $this->createLanguage();
        $owner = User::factory()->create();

        $word = $this->createWord($language, $owner);

        Sanctum::actingAs($owner);

        $response = $this->putJson(
            "/api/v1/words/{$word->id}",
            [
                'translation' => 'новий переклад',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.translation',
                'новий переклад'
            );

        $this->assertDatabaseHas('words', [
            'id' => $word->id,
            'translation' => 'новий переклад',
        ]);
    }

    public function test_user_cannot_delete_another_users_word(): void
    {
        $language = $this->createLanguage();

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $word = $this->createWord($language, $owner);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson(
            "/api/v1/words/{$word->id}"
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('words', [
            'id' => $word->id,
        ]);
    }

    public function test_owner_can_delete_own_word(): void
    {
        $language = $this->createLanguage();
        $owner = User::factory()->create();

        $word = $this->createWord($language, $owner);

        Sanctum::actingAs($owner);

        $response = $this->deleteJson(
            "/api/v1/words/{$word->id}"
        );

        $response->assertOk();

        $this->assertDatabaseMissing('words', [
            'id' => $word->id,
        ]);
    }
}

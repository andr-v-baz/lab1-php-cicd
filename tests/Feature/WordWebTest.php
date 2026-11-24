<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\User;
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

    private function loginAsUser(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function test_home_page_opens(): void
    {
        $this->get(route('home'))
            ->assertOk();
    }

    public function test_words_page_opens(): void
    {
        $this->get(route('words.index'))
            ->assertOk();
    }

    public function test_create_word_page_opens(): void
    {
        $this->loginAsUser();

        $this->get(route('words.create'))
            ->assertOk()
            ->assertViewIs('words.create');
    }

    public function test_languages_page_opens(): void
    {
        $this->get(route('languages.index'))
            ->assertOk();
    }

    public function test_word_can_be_created(): void
    {
        $user = $this->loginAsUser();

        $response = $this->post(route('words.store'), [
            'original' => 'apple',
            'translation' => 'яблуко',
        ]);

        $response->assertRedirect(route('words.index'));

        $this->assertDatabaseHas('words', [
            'original' => 'apple',
            'translation' => 'яблуко',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('translations', [
            'translated_text' => 'яблуко',
        ]);
    }

    public function test_invalid_word_shows_validation_errors(): void
    {
        $this->loginAsUser();

        $response = $this->from(route('words.create'))
            ->post(route('words.store'), [
                'original' => '12345',
                'translation' => '',
            ]);

        $response
            ->assertRedirect(route('words.create'))
            ->assertSessionHasErrors([
                'original',
                'translation',
            ]);

        $this->assertDatabaseMissing('words', [
            'original' => '12345',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('words.create'))
            ->assertRedirect(route('login'));

        $this->post(route('words.store'), [
            'original' => 'dog',
            'translation' => 'собака',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('words', [
            'original' => 'dog',
        ]);
    }
}

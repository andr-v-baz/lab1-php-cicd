<?php

namespace Tests\Feature;

use App\Jobs\SendWordCreatedNotificationJob;
use App\Models\Language;
use App\Models\User;
use App\Services\WordService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WordCreatedJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_word_dispatches_job(): void
    {
        Queue::fake();

        $english = Language::firstOrCreate(
            ['code' => 'en'],
            ['name' => 'English']
        );

        Language::firstOrCreate(
            ['code' => 'uk'],
            ['name' => 'Ukrainian']
        );

        $user = User::factory()->create();

        $word = app(WordService::class)->create([
            'original' => 'hello',
            'translation' => 'привіт',
            'language_id' => $english->id,
        ], $user->id);

        Queue::assertPushed(
            SendWordCreatedNotificationJob::class,
            function (SendWordCreatedNotificationJob $job) use ($word) {
                return $job->wordId === $word->id;
            }
        );
    }
}

<?php

namespace App\Jobs;

use App\Models\Word;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendWordCreatedNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $wordId
    ) {}

    public function handle(): void
    {
        $word = Word::find($this->wordId);

        if ($word === null) {
            return;
        }

        Log::info('Створено нове слово у словнику', [
            'word_id' => $word->id,
            'original' => $word->original,
            'user_id' => $word->user_id,
        ]);
    }
}

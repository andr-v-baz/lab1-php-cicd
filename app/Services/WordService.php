<?php

namespace App\Services;

use App\Jobs\SendWordCreatedNotificationJob;
use App\Models\Language;
use App\Models\Word;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WordService
{
    public function create(array $data, int $userId): Word
    {
        return DB::transaction(function () use ($data, $userId): Word {
            $english = Language::where('code', 'en')->firstOrFail();
            $ukrainian = Language::where('code', 'uk')->firstOrFail();

            $word = Word::create([
                'original' => trim($data['original']),
                'translation' => trim($data['translation']),
                'language_id' => $data['language_id'] ?? $english->id,
                'user_id' => $userId,
            ]);

            $word->translations()->create([
                'language_id' => $ukrainian->id,
                'translated_text' => $word->translation,
            ]);

            SendWordCreatedNotificationJob::dispatch($word->id)->afterCommit();

            return $word->load([
                'language',
                'translations.language',
                'user',
            ]);
        });
    }

    public function update(Word $word, array $data): Word
    {
        return DB::transaction(function () use ($word, $data): Word {
            $ukrainian = Language::where('code', 'uk')->firstOrFail();

            $translations = $word->translations()
                ->where('language_id', $ukrainian->id)
                ->orderBy('id')
                ->get();

            $primary = $translations->firstWhere(
                'translated_text',
                $word->translation
            ) ?? $translations->first();

            if (array_key_exists('translations', $data)) {
                $submitted = $data['translations'];

                $existingIds = $translations
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();

                $submittedIds = array_map(
                    'intval',
                    array_keys($submitted)
                );

                sort($submittedIds);

                if ($existingIds !== $submittedIds) {
                    throw ValidationException::withMessages([
                        'translations' => 'Неправильний список перекладів.',
                    ]);
                }

                $normalized = array_map(
                    fn ($value) => trim($value),
                    $submitted
                );

                if (count($normalized) !== count(array_unique($normalized))) {
                    throw ValidationException::withMessages([
                        'translations' => 'Переклади не повинні повторюватися.',
                    ]);
                }

                foreach ($translations as $translation) {
                    $translation->update([
                        'translated_text' => $normalized[$translation->id],
                    ]);
                }

                if ($primary !== null) {
                    $data['translation'] = $normalized[$primary->id];
                }
            } elseif (array_key_exists('translation', $data)) {
                $newTranslation = trim($data['translation']);

                if ($primary !== null) {
                    $primary->update([
                        'translated_text' => $newTranslation,
                    ]);
                } else {
                    $word->translations()->create([
                        'language_id' => $ukrainian->id,
                        'translated_text' => $newTranslation,
                    ]);
                }

                $data['translation'] = $newTranslation;
            }

            unset($data['translations']);

            if (array_key_exists('original', $data)) {
                $data['original'] = trim($data['original']);
            }

            $word->update($data);

            return $word->load([
                'language',
                'translations.language',
                'user',
            ]);
        });
    }

    public function delete(Word $word): void
    {
        DB::transaction(function () use ($word): void {
            $word->translations()->delete();
            $word->delete();
        });
    }
}

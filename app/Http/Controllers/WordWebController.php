<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Language;
use App\Models\User;
use App\Models\Word;
use App\Rules\DictionaryAlphabet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WordWebController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $direction = $request->query('direction') === 'uk-en'
            ? 'uk-en'
            : 'en-uk';

        $words = Word::with(['language', 'translations.language'])
            ->when($search !== '', function ($query) use ($search, $direction) {
                if ($direction === 'uk-en') {
                    $query->where(function ($query) use ($search) {
                        $query->where('translation', 'like', '%' . $search . '%')
                            ->orWhereHas('translations', function ($translationQuery) use ($search) {
                                $translationQuery
                                    ->where('translated_text', 'like', '%' . $search . '%')
                                    ->whereHas('language', function ($languageQuery) {
                                        $languageQuery->where('code', 'uk');
                                    });
                            });
                    });
                } else {
                    $query->where('original', 'like', '%' . $search . '%');
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $testUser = User::where('email', 'dictionary-test@example.com')->first();

        $favoriteWordIds = $testUser
            ? Favorite::where('user_id', $testUser->id)
                ->pluck('word_id')
                ->all()
            : [];

        return view('words.index', compact(
            'words',
            'search',
            'direction',
            'favoriteWordIds'
        ));
    }

    public function show(int $id): View
    {
        $word = Word::with(['language', 'translations.language'])
            ->findOrFail($id);

        return view('words.show', compact('word'));
    }

    public function create(): View
    {
        return view('words.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'original' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('en'),
            ],
            'translation' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('uk'),
            ],
        ]);

        $english = Language::where('code', 'en')->firstOrFail();
        $ukrainian = Language::where('code', 'uk')->firstOrFail();

        DB::transaction(function () use ($validated, $english, $ukrainian) {
            $word = Word::create([
                'original' => trim($validated['original']),
                'translation' => trim($validated['translation']),
                'language_id' => $english->id,
            ]);

            $word->translations()->create([
                'language_id' => $ukrainian->id,
                'translated_text' => trim($validated['translation']),
            ]);
        });

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово та переклад успішно додано!');
    }

    public function edit(int $id): View
    {
        $word = Word::with(['translations.language'])->findOrFail($id);

        return view('words.edit', compact('word'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $word = Word::with(['translations.language'])->findOrFail($id);

        $ukrainian = Language::where('code', 'uk')->firstOrFail();
        $english = Language::where('code', 'en')->firstOrFail();

        $existingTranslations = $word->translations
            ->where('language_id', $ukrainian->id);

        $rules = [
            'original' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('en'),
            ],
        ];

        if ($existingTranslations->isNotEmpty()) {
            $rules['translations'] = ['required', 'array'];

            foreach ($existingTranslations as $translation) {
                $rules['translations.' . $translation->id] = [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    new DictionaryAlphabet('uk'),
                ];
            }
        } else {
            $rules['translation'] = [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('uk'),
            ];
        }

        $validated = $request->validate($rules);

        if ($existingTranslations->isNotEmpty()) {
            $submitted = $validated['translations'];

            $expectedIds = $existingTranslations
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->sort()
                ->values()
                ->all();

            $submittedIds = array_keys($submitted);
            $submittedIds = array_map('strval', $submittedIds);
            sort($submittedIds);

            if ($expectedIds !== $submittedIds) {
                throw ValidationException::withMessages([
                    'translations' => 'Список перекладів некоректний.',
                ]);
            }

            $values = array_map('trim', array_values($submitted));

            if (count($values) !== count(array_unique($values))) {
                throw ValidationException::withMessages([
                    'translations' => 'Переклади одного слова не повинні повторюватися.',
                ]);
            }
        }

        DB::transaction(function () use (
            $word,
            $validated,
            $english,
            $ukrainian,
            $existingTranslations
        ) {
            if ($existingTranslations->isNotEmpty()) {
                $primary = $existingTranslations
                    ->firstWhere('translated_text', $word->translation);

                $primary ??= $existingTranslations->sortBy('id')->first();

                foreach ($existingTranslations as $translation) {
                    $translation->update([
                        'translated_text' => trim(
                            $validated['translations'][$translation->id]
                        ),
                    ]);
                }

                $newPrimaryTranslation = trim(
                    $validated['translations'][$primary->id]
                );
            } else {
                $newPrimaryTranslation = trim($validated['translation']);

                $word->translations()->create([
                    'language_id' => $ukrainian->id,
                    'translated_text' => $newPrimaryTranslation,
                ]);
            }

            $word->update([
                'original' => trim($validated['original']),
                'translation' => $newPrimaryTranslation,
                'language_id' => $english->id,
            ]);
        });

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово та всі переклади успішно оновлено!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $word = Word::findOrFail($id);

        DB::transaction(function () use ($word) {
            $word->translations()->delete();
            $word->delete();
        });

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово та всі його переклади успішно видалено!');
    }
}

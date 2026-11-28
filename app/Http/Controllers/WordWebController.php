<?php

namespace App\Http\Controllers;

use App\Http\Requests\Word\StoreWordRequest;
use App\Http\Requests\Word\UpdateWordRequest;
use App\Models\Favorite;
use App\Models\Language;
use App\Models\Word;
use App\Services\WordService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WordWebController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly WordService $wordService
    ) {}

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
                        $query->where('translation', 'like', '%'.$search.'%')
                            ->orWhereHas('translations', function ($translationQuery) use ($search) {
                                $translationQuery
                                    ->where('translated_text', 'like', '%'.$search.'%')
                                    ->whereHas('language', function ($languageQuery) {
                                        $languageQuery->where('code', 'uk');
                                    });
                            });
                    });
                } else {
                    $query->where('original', 'like', '%'.$search.'%');
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $favoriteWordIds = $request->user()
            ? Favorite::where('user_id', $request->user()->id)
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
        $this->authorize('create', Word::class);

        return view('words.create');
    }

    public function store(StoreWordRequest $request): RedirectResponse
    {
        $this->authorize('create', Word::class);

        $this->wordService->create(
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово та переклад успішно додано!');
    }

    public function edit(int $id): View
    {
        $word = Word::with(['translations.language'])
            ->findOrFail($id);

        $this->authorize('update', $word);

        return view('words.edit', compact('word'));
    }

    public function update(
        UpdateWordRequest $request,
        int $id
    ): RedirectResponse {
        $word = Word::with(['translations.language'])
            ->findOrFail($id);

        $this->authorize('update', $word);

        $ukrainian = Language::where('code', 'uk')
            ->firstOrFail();

        $existingTranslations = $word->translations
            ->where('language_id', $ukrainian->id);

        $validated = $request->validated();

        if (! array_key_exists('original', $validated)) {
            throw ValidationException::withMessages([
                'original' => 'Вкажіть слово для редагування.',
            ]);
        }

        if ($existingTranslations->isNotEmpty()) {
            if (! array_key_exists('translations', $validated)) {
                throw ValidationException::withMessages([
                    'translations' => 'Вкажіть усі переклади.',
                ]);
            }
        } elseif (! array_key_exists('translation', $validated)) {
            throw ValidationException::withMessages([
                'translation' => 'Вкажіть переклад.',
            ]);
        }

        $english = Language::where('code', 'en')
            ->firstOrFail();

        $validated['language_id'] = $english->id;

        $this->wordService->update($word, $validated);

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово та всі переклади успішно оновлено!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $word = Word::findOrFail($id);

        $this->authorize('delete', $word);

        $this->wordService->delete($word);

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово та всі його переклади успішно видалено!');
    }
}

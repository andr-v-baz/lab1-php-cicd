<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WordResource;
use App\Models\Word;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class WordController extends Controller
{
    use AuthorizesRequests;

    // Отримати список усіх слів
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Word::class);

        $words = Word::with([
            'language',
            'translations.language',
            'user',
        ])
            ->latest('id')
            ->paginate(10);

        return WordResource::collection($words);
    }

    // Переглянути конкретне слово
    public function show(Word $word): WordResource
    {
        $this->authorize('view', $word);

        $word->load([
            'language',
            'translations.language',
            'user',
        ]);

        return new WordResource($word);
    }

    // Додати нове слово
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Word::class);

        $validated = $request->validate([
            'original' => ['required', 'string', 'max:255'],
            'translation' => ['required', 'string', 'max:255'],
            'language_id' => [
                'required',
                'integer',
                Rule::exists('languages', 'id'),
            ],
        ]);

        $word = Word::create([
            'original' => $validated['original'],
            'translation' => $validated['translation'],
            'language_id' => $validated['language_id'],
            'user_id' => $request->user()->id,
        ]);

        $word->load([
            'language',
            'translations.language',
            'user',
        ]);

        return response()->json([
            'message' => 'Слово успішно додано.',
            'data' => (new WordResource($word))->resolve($request),
        ], 201);
    }

    // Оновити слово
    public function update(Request $request, Word $word): JsonResponse
    {
        $this->authorize('update', $word);

        $validated = $request->validate([
            'original' => ['sometimes', 'required', 'string', 'max:255'],
            'translation' => ['sometimes', 'required', 'string', 'max:255'],
            'language_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('languages', 'id'),
            ],
        ]);

        $word->update($validated);

        $word->load([
            'language',
            'translations.language',
            'user',
        ]);

        return response()->json([
            'message' => 'Слово успішно оновлено.',
            'data' => (new WordResource($word))->resolve($request),
        ]);
    }

    // Видалити слово
    public function destroy(Word $word): JsonResponse
    {
        $this->authorize('delete', $word);

        $word->delete();

        return response()->json([
            'message' => 'Слово успішно видалено.',
        ]);
    }
}

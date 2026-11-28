<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Word\StoreWordRequest;
use App\Http\Requests\Word\UpdateWordRequest;
use App\Http\Resources\WordResource;
use App\Models\Word;
use App\Services\WordService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WordController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly WordService $wordService
    ) {}

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

    public function store(StoreWordRequest $request): JsonResponse
    {
        $this->authorize('create', Word::class);

        $word = $this->wordService->create(
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Слово успішно додано.',
            'data' => (new WordResource($word))->resolve($request),
        ], 201);
    }

    public function update(
        UpdateWordRequest $request,
        Word $word
    ): JsonResponse {
        $this->authorize('update', $word);

        $word = $this->wordService->update(
            $word,
            $request->validated()
        );

        return response()->json([
            'message' => 'Слово успішно оновлено.',
            'data' => (new WordResource($word))->resolve($request),
        ]);
    }

    public function destroy(Word $word): JsonResponse
    {
        $this->authorize('delete', $word);

        $this->wordService->delete($word);

        return response()->json([
            'message' => 'Слово успішно видалено.',
        ]);
    }
}

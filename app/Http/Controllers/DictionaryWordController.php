<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DictionaryWordController extends Controller
{
    private static array $words = [
        1 => [
            'id' => 1,
            'word' => 'hello',
            'translation' => 'привіт',
            'language' => 'en',
            'part_of_speech' => 'interjection',
        ],
        2 => [
            'id' => 2,
            'word' => 'book',
            'translation' => 'книга',
            'language' => 'en',
            'part_of_speech' => 'noun',
        ],
    ];

    public function index(): JsonResponse
    {
        return response()->json(array_values(self::$words), 200);
    }

    public function show(int $id): JsonResponse
    {
        if (! isset(self::$words[$id])) {
            return response()->json([
                'message' => 'Word not found',
            ], 404);
        }

        return response()->json(self::$words[$id], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'word' => 'required|string|min:2',
            'translation' => 'required|string|min:2',
            'language' => 'required|string|size:2',
            'part_of_speech' => 'required|string',
        ]);

        $id = max(array_keys(self::$words)) + 1;

        $newWord = array_merge(['id' => $id], $validated);

        self::$words[$id] = $newWord;

        return response()->json($newWord, 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteWebController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = Favorite::with('word')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'word_id' => ['required', 'integer', 'exists:words,id'],
        ]);

        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'word_id' => $validated['word_id'],
        ]);

        return back()->with('success', 'Слово додано в обране!');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('word_id', $id)
            ->delete();

        return back()->with('success', 'Слово прибрано з обраного!');
    }
}

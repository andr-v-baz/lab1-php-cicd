<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class FavoriteWebController extends Controller
{
    private function testUserId(): int
    {
        $user = User::firstOrCreate(
            ['email' => 'dictionary-test@example.com'],
            [
                'name' => 'Dictionary Test',
                'password' => Hash::make('TestPassword123!'),
            ]
        );

        return $user->id;
    }

    public function index(): View
    {
        $favorites = Favorite::with('word')
            ->where('user_id', $this->testUserId())
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
            'user_id' => $this->testUserId(),
            'word_id' => $validated['word_id'],
        ]);

        return redirect()
            ->route('words.index')
            ->with('success', 'Слово додано в обране!');
    }

    public function destroy(int $id): RedirectResponse
    {
        Favorite::where('user_id', $this->testUserId())
            ->where('word_id', $id)
            ->delete();

        return back()->with('success', 'Слово прибрано з обраного!');
    }
}

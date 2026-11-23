<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SearchHistoryWebController extends Controller
{
    public function index(): View
    {
        $user = User::where(
            'email',
            'dictionary-test@example.com'
        )->first();

        $histories = $user
            ? SearchHistory::with('word')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(15)
            : SearchHistory::query()->whereRaw('1 = 0')->paginate(15);

        return view('search-history.index', compact('histories'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::where(
            'email',
            'dictionary-test@example.com'
        )->firstOrFail();

        SearchHistory::where('user_id', $user->id)
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('search-history.index')
            ->with('success', 'Запис історії видалено!');
    }
}

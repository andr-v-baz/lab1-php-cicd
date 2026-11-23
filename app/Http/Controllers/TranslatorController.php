<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use App\Models\User;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TranslatorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $direction = $request->query('direction') === 'uk-en'
            ? 'uk-en'
            : 'en-uk';

        $words = collect();

        if ($search !== '') {
            $words = Word::with(['translations.language'])
                ->where(function ($query) use ($search, $direction) {
                    if ($direction === 'uk-en') {
                        $query->where('translation', 'like', '%' . $search . '%')
                            ->orWhereHas('translations', function ($translationQuery) use ($search) {
                                $translationQuery
                                    ->where('translated_text', 'like', '%' . $search . '%')
                                    ->whereHas('language', function ($languageQuery) {
                                        $languageQuery->where('code', 'uk');
                                    });
                            });
                    } else {
                        $query->where('original', 'like', '%' . $search . '%');
                    }
                })
                ->orderBy('original')
                ->get();

            $user = User::firstOrCreate(
                ['email' => 'dictionary-test@example.com'],
                [
                    'name' => 'Dictionary Test',
                    'password' => Hash::make('TestPassword123!'),
                ]
            );

            SearchHistory::create([
                'user_id' => $user->id,
                'word_id' => $words->first()?->id,
                'search_query' => mb_substr($search, 0, 255),
            ]);
        }

        return view('translator.index', compact(
            'words',
            'search',
            'direction'
        ));
    }
}

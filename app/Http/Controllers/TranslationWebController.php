<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Translation;
use App\Models\Word;
use App\Rules\DictionaryAlphabet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TranslationWebController extends Controller
{
    public function index(): View
    {
        $translations = Translation::with(['word', 'language'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('translations.index', compact('translations'));
    }

    public function create(Request $request): View
    {
        $word = Word::findOrFail($request->query('word_id'));

        return view('translations.create', compact('word'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'word_id' => ['required', 'integer', 'exists:words,id'],
            'translated_text' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('uk'),
            ],
        ]);

        $word = Word::findOrFail($validated['word_id']);
        $ukrainian = Language::where('code', 'uk')->firstOrFail();

        $translation = trim($validated['translated_text']);

        $exists = $word->translation === $translation
            || $word->translations()
                ->where('language_id', $ukrainian->id)
                ->where('translated_text', $translation)
                ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'translated_text' => 'Такий переклад уже існує для цього слова.',
                ]);
        }

        $word->translations()->create([
            'language_id' => $ukrainian->id,
            'translated_text' => $translation,
        ]);

        return redirect()
            ->route('words.index')
            ->with('success', 'Додатковий переклад успішно додано!');
    }

    public function edit(int $id): View
    {
        $translation = Translation::with('word')->findOrFail($id);

        return view('translations.edit', compact('translation'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $translation = Translation::with('word')->findOrFail($id);

        $validated = $request->validate([
            'translated_text' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new DictionaryAlphabet('uk'),
            ],
        ]);

        $ukrainian = Language::where('code', 'uk')->firstOrFail();

        if ($translation->language_id !== $ukrainian->id) {
            abort(404);
        }

        $newText = trim($validated['translated_text']);

        $exists = $translation->word->translation === $newText
            && $translation->translated_text !== $newText;

        $exists = $exists || Translation::where('word_id', $translation->word_id)
            ->where('language_id', $ukrainian->id)
            ->where('translated_text', $newText)
            ->where('id', '!=', $translation->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'translated_text' => 'Такий переклад уже існує для цього слова.',
                ]);
        }

        if ($translation->word->translation === $translation->translated_text) {
            $translation->word->update([
                'translation' => $newText,
            ]);
        }

        $translation->update([
            'translated_text' => $newText,
        ]);

        return redirect()
            ->route('words.index')
            ->with('success', 'Переклад успішно оновлено!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $translation = Translation::with('word')->findOrFail($id);

        $ukrainian = Language::where('code', 'uk')->firstOrFail();

        if ($translation->language_id !== $ukrainian->id) {
            abort(404);
        }

        if ($translation->word->translation === $translation->translated_text) {
            return redirect()
                ->route('words.index')
                ->withErrors([
                    'translated_text' => 'Основний переклад потрібно редагувати через форму редагування слова.',
                ]);
        }

        $translation->delete();

        return redirect()
            ->route('words.index')
            ->with('success', 'Додатковий переклад успішно видалено!');
    }
}

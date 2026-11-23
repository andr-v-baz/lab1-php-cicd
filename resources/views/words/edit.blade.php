
@extends('layouts.app')

@section('title', 'Редагувати слово')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Редагувати слово</h1>

            <form action="{{ route('words.update', $word->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form.input
                    name="original"
                    label="Англійське слово"
                    :value="$word->original"
                    required
                />

                @php
                    $translations = $word->translations
                        ->filter(fn ($translation) => $translation->language?->code === 'uk')
                        ->unique('translated_text')
                        ->values();

                    $primary = $translations->firstWhere('translated_text', $word->translation);

                    if ($primary) {
                        $translations = $translations
                            ->reject(fn ($translation) => $translation->id === $primary->id)
                            ->prepend($primary)
                            ->values();
                    }
                @endphp

                @forelse ($translations as $index => $translation)
                    <div class="mb-3">
                        <label for="translations_{{ $translation->id }}" class="form-label">
                            Переклад {{ $index + 1 }}
                        </label>

                        <input
                            type="text"
                            name="translations[{{ $translation->id }}]"
                            id="translations_{{ $translation->id }}"
                            class="form-control @error('translations.' . $translation->id) is-invalid @enderror"
                            value="{{ old('translations.' . $translation->id, $translation->translated_text) }}"
                            required
                        >

                        @error('translations.' . $translation->id)
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @empty
                    <x-form.input
                        name="translation"
                        label="Переклад 1"
                        :value="$word->translation"
                        required
                    />
                @endforelse

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        Зберегти зміни
                    </button>

                    <a href="{{ route('words.index') }}" class="btn btn-outline-secondary">
                        Скасувати
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

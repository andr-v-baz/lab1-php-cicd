
@extends('layouts.app')

@section('title', 'Редагувати переклад')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">
            <h1 class="h3 mb-3">Редагувати переклад</h1>

            <p class="text-muted mb-4">
                Англійське слово:
                <strong>{{ $translation->word->original }}</strong>
            </p>

            <form action="{{ route('translations.update', $translation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="translated_text" class="form-label">
                        Український переклад
                    </label>

                    <input
                        type="text"
                        name="translated_text"
                        id="translated_text"
                        class="form-control @error('translated_text') is-invalid @enderror"
                        value="{{ old('translated_text', $translation->translated_text) }}"
                        required
                        autofocus
                    >

                    @error('translated_text')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        Зберегти зміни
                    </button>

                    <a href="{{ route('translations.index') }}" class="btn btn-outline-secondary">
                        Скасувати
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

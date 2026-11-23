
@extends('layouts.app')

@section('title', 'Додати переклад')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4">Додати переклад</h1>

                    <form action="{{ route('translations.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="word_id" value="{{ $word->id }}">

                        <div class="mb-4">
                            <label for="translated_text" class="form-label">
                                Новий український переклад
                            </label>

                            <input
                                type="text"
                                name="translated_text"
                                id="translated_text"
                                class="form-control @error('translated_text') is-invalid @enderror"
                                value="{{ old('translated_text') }}"
                                required
                                autofocus
                            >

                            @error('translated_text')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('words.index') }}" class="btn btn-secondary">
                                Скасувати
                            </a>

                            <button type="submit" class="btn btn-success">
                                Додати переклад
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

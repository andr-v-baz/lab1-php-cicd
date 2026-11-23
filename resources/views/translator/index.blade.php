
@extends('layouts.app')

@section('title', 'Перекладач')

@section('content')
    <div class="text-center mb-5">
        <h1 class="fw-bold">Перекладач</h1>
        <p class="text-muted">
            Перекладайте слова з англійської на українську та навпаки
        </p>
    </div>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 850px;">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('translator.index') }}" method="GET">
                <label class="form-label fw-semibold">
                    Напрямок перекладу
                </label>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <label class="btn {{ $direction === 'en-uk' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <input
                            type="radio"
                            name="direction"
                            value="en-uk"
                            class="d-none"
                            {{ $direction === 'en-uk' ? 'checked' : '' }}
                            onchange="this.form.submit()"
                        >
                        🇬🇧 Англійська → Українська 🇺🇦
                    </label>

                    <label class="btn {{ $direction === 'uk-en' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <input
                            type="radio"
                            name="direction"
                            value="uk-en"
                            class="d-none"
                            {{ $direction === 'uk-en' ? 'checked' : '' }}
                            onchange="this.form.submit()"
                        >
                        🇺🇦 Українська → Англійська 🇬🇧
                    </label>
                </div>

                <label for="search" class="form-label fw-semibold">
                    Введіть слово для перекладу
                </label>

                <div class="input-group">
                    <input
                        type="search"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="{{ $direction === 'en-uk' ? 'Наприклад: hello' : 'Наприклад: привіт' }}"
                        value="{{ $search }}"
                        autocomplete="off"
                    >

                    <button type="submit" class="btn btn-primary">
                        Перекласти
                    </button>
                </div>
            </form>

            @if ($search !== '')
                <hr class="my-4">

                <h2 class="h5 mb-3">Результат перекладу</h2>

                @forelse ($words as $word)
                    <div class="border rounded p-3 mb-2 bg-light">
                        <div class="text-muted small mb-1">
                            {{ $direction === 'en-uk' ? $word->original : $word->translation }}
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ $direction === 'en-uk' ? $word->translation : $word->original }}
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info mb-0">
                        Слово "{{ $search }}" не знайдено у словнику.
                    </div>
                @endforelse
            @endif
        </div>
    </div>
@endsection

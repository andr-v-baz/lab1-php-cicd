
@extends('layouts.app')

@section('title', 'Обрані слова')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">⭐ Обрані слова</h1>
            <p class="text-muted mb-0">
                Слова, які ти зберегла для швидкого доступу
            </p>
        </div>

        <a href="{{ route('words.index') }}" class="btn btn-outline-primary">
            ← До словника
        </a>
    </div>

    @if ($favorites->isEmpty())
        <div class="alert alert-info">
            У тебе поки немає обраних слів.
            Перейди до словника та натисни ☆ біля потрібного слова.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Англійське слово</th>
                            <th>Український переклад</th>
                            <th class="text-end pe-4">Обране</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($favorites as $favorite)
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    {{ $favorite->word?->original ?? 'Слово видалено' }}
                                </td>

                                <td>
                                    {{ $favorite->word?->translation ?? '—' }}
                                </td>

                                <td class="text-end pe-4">
                                    <form
                                        action="{{ route('favorites.destroy', $favorite->word_id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-link text-warning fs-4 p-0 text-decoration-none"
                                            title="Прибрати з обраного"
                                            aria-label="Прибрати з обраного"
                                        >
                                            ★
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection


@extends('layouts.app')

@section('title', 'Словник')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Словник</h1>
            <p class="text-muted mb-0">
                Усі слова та їхні переклади
            </p>
        </div>

        @auth
            @can('create', \App\Models\Word::class)
                <a
                    href="{{ route('words.create') }}"
                    class="btn btn-success"
                >
                    + Додати слово
                </a>
            @endcan
        @endauth
    </div>

    @if ($words->isEmpty())
        <div class="alert alert-info">
            У словнику поки немає слів.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Англійське слово</th>
                            <th>Українські переклади</th>

                            @auth
                                <th class="text-center">Обране</th>
                                <th class="text-end pe-4">Дії</th>
                            @endauth
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($words as $word)
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    {{ $word->original }}
                                </td>

                                <td>
                                    @php
                                        $translations = $word->translations
                                            ->filter(
                                                fn ($translation) =>
                                                    $translation->language?->code === 'uk'
                                            )
                                            ->pluck('translated_text')
                                            ->filter()
                                            ->unique()
                                            ->values();

                                        if (
                                            $word->translation &&
                                            !$translations->contains($word->translation)
                                        ) {
                                            $translations->prepend($word->translation);
                                        }
                                    @endphp

                                    {{ $translations->implode(', ') ?: 'Перекладів немає' }}
                                </td>

                                @auth
                                    <td class="text-center">
                                        @php
                                            $isFavorite = in_array(
                                                $word->id,
                                                $favoriteWordIds ?? []
                                            );
                                        @endphp

                                        @if ($isFavorite)
                                            <form
                                                action="{{ route('favorites.destroy', $word->id) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-link fs-3 p-0 text-decoration-none"
                                                    style="color: #ffc107;"
                                                    title="Прибрати з обраного"
                                                    aria-label="Прибрати з обраного"
                                                >
                                                    ★
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('favorites.store') }}"
                                                method="POST"
                                            >
                                                @csrf

                                                <input
                                                    type="hidden"
                                                    name="word_id"
                                                    value="{{ $word->id }}"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-link fs-3 p-0 text-decoration-none"
                                                    style="color: #6c757d;"
                                                    title="Додати в обране"
                                                    aria-label="Додати в обране"
                                                >
                                                    ☆
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-light border btn-sm"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                aria-label="Меню дій"
                                            >
                                                &#8942;
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <span class="dropdown-item-text text-muted small">
                                                        👤 Власник:
                                                        @if ($word->user_id === auth()->id())
                                                            Ви
                                                        @elseif ($word->user)
                                                            {{ $word->user->name }}
                                                        @else
                                                            Системне слово
                                                        @endif
                                                    </span>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('words.show', $word->id) }}"
                                                    >
                                                        👁 Переглянути слово
                                                    </a>
                                                </li>

                                                @can('update', $word)
                                                    <li>
                                                        <a
                                                            class="dropdown-item text-success"
                                                            href="{{ route('translations.create', ['word_id' => $word->id]) }}"
                                                        >
                                                            + Додати переклад
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route('words.edit', $word->id) }}"
                                                        >
                                                            ✏️ Редагувати слово
                                                        </a>
                                                    </li>
                                                @endcan

                                                @can('delete', $word)
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <form
                                                            action="{{ route('words.destroy', $word->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Точно видалити це слово та всі його переклади?')"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                            >
                                                                🗑 Видалити слово
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $words->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection

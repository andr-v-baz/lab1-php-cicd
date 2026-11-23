
@extends('layouts.app')

@section('title', 'Переклади')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">Переклади</h1>
            <p class="text-muted mb-0">
                Список перекладів слів різними мовами
            </p>
        </div>

        <a href="{{ route('translations.create') }}" class="btn btn-success">
            + Додати переклад
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Слово</th>
                        <th>Мова перекладу</th>
                        <th>Переклад</th>
                        <th class="text-end">Дії</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($translations as $translation)
                        <tr>
                            <td>{{ $translation->word?->original ?? '—' }}</td>
                            <td>{{ $translation->language?->name ?? '—' }}</td>
                            <td>{{ $translation->translated_text }}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-sm btn-outline-secondary"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        ⋮
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a
                                                class="dropdown-item"
                                                href="{{ route('translations.edit', $translation->id) }}"
                                            >
                                                Редагувати
                                            </a>
                                        </li>

                                        <li>
                                            <form
                                                action="{{ route('translations.destroy', $translation->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Видалити цей переклад?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger"
                                                >
                                                    Видалити
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Перекладів поки немає.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $translations->links('pagination::bootstrap-5') }}
    </div>
@endsection

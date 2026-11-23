
@extends('layouts.app')

@section('title', 'Історія пошуку')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">🕘 Історія пошуку</h1>
        <p class="text-muted mb-0">
            Твої попередні пошукові запити
        </p>
    </div>

    <a href="{{ route('translator.index') }}"
       class="btn btn-outline-primary">
        ← До перекладача
    </a>
</div>

@if ($histories->isEmpty())
    <div class="alert alert-info">
        Історія поки порожня. Спробуй знайти слово в перекладачі.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Пошуковий запит</th>
                        <th>Знайдене слово</th>
                        <th>Дата та час</th>
                        <th class="text-end pe-4">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                        <tr>
                            <td class="ps-4 fw-semibold">
                                {{ $history->search_query }}
                            </td>
                            <td>
                                {{ $history->word?->original ?? '—' }}
                            </td>
                            <td>
                                {{ $history->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="text-end pe-4">
                                <form
                                    action="{{ route('search-history.destroy', $history->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Видалити цей запис?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        Видалити
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $histories->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection

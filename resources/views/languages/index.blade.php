
@extends('layouts.app')

@section('title', 'Мови словника')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Мови словника</h1>

            <p class="text-muted">
                Словник підтримує англійську та українську мови.
            </p>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Назва мови</th>
                            <th>Код мови</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($languages as $language)
                            <tr>
                                <td>{{ $language->id }}</td>
                                <td>{{ $language->name }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $language->code }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Мови не знайдено.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('words.index') }}" class="btn btn-outline-secondary">
                Повернутися до словника
            </a>
        </div>
    </div>
@endsection

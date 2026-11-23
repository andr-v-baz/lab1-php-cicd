
@extends('layouts.app')

@section('title', 'Додати слово')

@section('content')
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Додати нове слово</h1>

            <form action="{{ route('words.store') }}" method="POST">
                @csrf

                <x-form.input
                    name="original"
                    label="Англійське слово"
                    required
                />

                <x-form.input
                    name="translation"
                    label="Український переклад"
                    required
                />

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        Зберегти
                    </button>

                    <a href="{{ route('words.index') }}" class="btn btn-outline-secondary">
                        Скасувати
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

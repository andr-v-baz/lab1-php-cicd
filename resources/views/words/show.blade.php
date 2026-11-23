
@extends('layouts.app')

@section('title', 'Перегляд слова')

@section('content')
    <h1 class="mb-4">Інформація про слово</h1>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $word->original }}</h2>

            <p class="card-text">
                <strong>Переклад:</strong>
                {{ $word->translation }}
            </p>

            <p class="card-text">
                <strong>Мова:</strong>
                {{ $word->language?->name ?? 'Не вказано' }}
            </p>

            <a href="{{ route('words.index') }}" class="btn btn-secondary">
                Назад до списку
            </a>
        </div>
    </div>
@endsection

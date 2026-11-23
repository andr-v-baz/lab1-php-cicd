
@extends('layouts.app')

@section('title', 'Головна сторінка')

@section('content')
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <h1 class="display-5 fw-bold">
            Електронний словник
        </h1>

        <p class="fs-4">
            Вітаємо в електронному словнику!
            Тут можна перекладати слова з англійської
            на українську та навпаки, а також переглядати,
            додавати, редагувати та видаляти слова.
        </p>

        <div class="d-flex gap-3 flex-wrap">
            <a
                href="{{ route('translator.index') }}"
                class="btn btn-primary btn-lg"
            >
                Перейти до перекладача
            </a>

            <a
                href="{{ route('words.index') }}"
                class="btn btn-outline-primary btn-lg"
            >
                Переглянути словник
            </a>
        </div>
    </div>
@endsection

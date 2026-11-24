
@extends('layouts.app')

@section('title', 'Головна сторінка')

@section('content')
    <div class="p-4 p-md-5 mb-4 bg-light rounded-3 shadow-sm">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold mb-3">
                🦉 Електронний словник
            </h1>

            <p class="fs-5 text-secondary mx-auto"
               style="max-width: 800px;">
                Перекладай слова з англійської на українську
                та навпаки, знаходь потрібні переклади
                й поповнюй свій словниковий запас!
            </p>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
            <a href="{{ route('translator.index') }}"
               class="btn btn-primary btn-lg px-4">
                🌐 Перекладач
            </a>

            <a href="{{ route('words.index') }}"
               class="btn btn-outline-primary btn-lg px-4">
                📖 Переглянути словник
            </a>
        </div>

        @guest
            <hr class="my-4">

            <div class="text-center mx-auto"
                 style="max-width: 650px;">
                <h2 class="h3 fw-bold mb-3">
                    Приєднуйся до нашого словника!
                </h2>

                <p class="text-secondary mb-0">
                    Створи акаунт, щоб додавати власні слова,
                    редагувати їх та зберігати улюблені переклади.
                    Для реєстрації або входу скористайся
                    верхнім меню.
                </p>
            </div>
        @endguest

        @auth
            <hr class="my-4">

            <div class="text-center">
                <h2 class="h3 fw-bold mb-3">
                    Вітаємо, {{ auth()->user()->name }}! 👋
                </h2>

                <p class="text-secondary mb-4">
                    Тепер ти можеш додавати власні слова
                    та керувати ними.
                </p>

                @can('create', \App\Models\Word::class)
                    <a href="{{ route('words.create') }}"
                       class="btn btn-success btn-lg">
                        ➕ Додати слово
                    </a>
                @endcan
            </div>
        @endauth
    </div>
@endsection

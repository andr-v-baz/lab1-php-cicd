
@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">

                <h1 class="h3 text-center fw-bold mb-2">
                    🦉 Реєстрація
                </h1>

                <p class="text-muted text-center mb-4">
                    Створи акаунт, щоб додавати власні слова
                    та зберігати улюблені переклади.
                </p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Ім'я
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required
                            autofocus
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Електронна пошта
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Пароль
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            required
                        >

                        <div class="form-text">
                            Мінімум 8 символів.
                        </div>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Підтвердження пароля
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            minlength="8"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn btn-success btn-lg w-100"
                    >
                        Зареєструватися
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    Уже маєш акаунт?
                    <a href="{{ route('login') }}"
                       class="text-success fw-semibold">
                        Увійти
                    </a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection

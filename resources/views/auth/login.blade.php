
@extends('layouts.app')

@section('title', 'Вхід')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">

                <h1 class="h3 text-center fw-bold mb-2">
                    🦉 Вхід до словника
                </h1>

                <p class="text-muted text-center mb-4">
                    Увійди, щоб керувати власними словами.
                </p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

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
                            autofocus
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
                            required
                        >

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                            class="form-check-input"
                        >

                        <label for="remember" class="form-check-label">
                            Запам'ятати мене
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-success btn-lg w-100"
                    >
                        Увійти
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    Ще немає акаунта?
                    <a href="{{ route('register') }}"
                       class="text-success fw-semibold">
                        Зареєструватися
                    </a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection

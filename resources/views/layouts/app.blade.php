
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Електронний словник')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                🦉 Електронний словник
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
                aria-label="Відкрити меню"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}"
                        >
                            Головна
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('translator.*') ? 'active' : '' }}"
                            href="{{ route('translator.index') }}"
                        >
                            Перекладач
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('words.*') ? 'active' : '' }}"
                            href="{{ route('words.index') }}"
                        >
                            Словник
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('languages.*') ? 'active' : '' }}"
                            href="{{ route('languages.index') }}"
                        >
                            🌐 Мови
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}"
                            href="{{ route('favorites.index') }}"
                        >
                            ⭐ Обране
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('search-history.*') ? 'active' : '' }}"
                            href="{{ route('search-history.index') }}"
                        >
                            🕘 Історія
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">

        @if (session('success'))
            <div
                id="success-alert"
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Закрити"
                ></button>
            </div>
        @endif

        @if (session('error'))
            <div
                id="error-alert"
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >
                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Закрити"
                ></button>
            </div>
        @endif

        @yield('content')

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ['success-alert', 'error-alert'].forEach(function (id) {
                const alert = document.getElementById(id);

                if (alert) {
                    setTimeout(function () {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }, 3000);
                }
            });
        });
    </script>

</body>
</html>

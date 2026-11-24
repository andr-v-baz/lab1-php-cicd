<?php

use App\Http\Controllers\DictionaryInfoController;
use App\Http\Controllers\FavoriteWebController;
use App\Http\Controllers\LanguageWebController;
use App\Http\Controllers\SearchHistoryWebController;
use App\Http\Controllers\TranslationWebController;
use App\Http\Controllers\TranslatorController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\WordWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/translator', [TranslatorController::class, 'index'])
    ->name('translator.index');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'dictionary',
    ]);
})->name('health');

Route::get('/dictionary/info', [DictionaryInfoController::class, 'index'])
    ->name('dictionary.info');

// Авторизація через вебформи
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [WebAuthController::class, 'login']);

    Route::get('/register', [WebAuthController::class, 'showRegisterForm'])
        ->name('register');

    Route::post('/register', [WebAuthController::class, 'register']);
});

Route::post('/logout', [WebAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Словник: публічний перегляд
Route::prefix('words')->name('words.')->group(function () {
    Route::get('/', [WordWebController::class, 'index'])
        ->name('index');

    Route::get('/{id}', [WordWebController::class, 'show'])
        ->whereNumber('id')
        ->name('show');

    // Створення, редагування та видалення — лише після входу
    Route::middleware('auth')->group(function () {
        Route::get('/create', [WordWebController::class, 'create'])
            ->name('create');

        Route::post('/', [WordWebController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [WordWebController::class, 'edit'])
            ->whereNumber('id')
            ->name('edit');

        Route::put('/{id}', [WordWebController::class, 'update'])
            ->whereNumber('id')
            ->name('update');

        Route::delete('/{id}', [WordWebController::class, 'destroy'])
            ->whereNumber('id')
            ->name('destroy');
    });
});

// Переклади: публічний список
Route::prefix('translations')->name('translations.')->group(function () {
    Route::get('/', [TranslationWebController::class, 'index'])
        ->name('index');

    // Зміна перекладів — лише після входу
    Route::middleware('auth')->group(function () {
        Route::get('/create', [TranslationWebController::class, 'create'])
            ->name('create');

        Route::post('/', [TranslationWebController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [TranslationWebController::class, 'edit'])
            ->whereNumber('id')
            ->name('edit');

        Route::put('/{id}', [TranslationWebController::class, 'update'])
            ->whereNumber('id')
            ->name('update');

        Route::delete('/{id}', [TranslationWebController::class, 'destroy'])
            ->whereNumber('id')
            ->name('destroy');
    });
});

// Публічний список мов
Route::get('/languages', [LanguageWebController::class, 'index'])
    ->name('languages.index');

// Обране та історія пошуку
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteWebController::class, 'index'])
        ->name('favorites.index');

    Route::post('/favorites', [FavoriteWebController::class, 'store'])
        ->name('favorites.store');

    Route::delete('/favorites/{id}', [FavoriteWebController::class, 'destroy'])
        ->whereNumber('id')
        ->name('favorites.destroy');

    Route::get('/search-history', [SearchHistoryWebController::class, 'index'])
        ->name('search-history.index');

    Route::delete('/search-history/{id}', [SearchHistoryWebController::class, 'destroy'])
        ->whereNumber('id')
        ->name('search-history.destroy');
});

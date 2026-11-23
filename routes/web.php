<?php

use App\Http\Controllers\DictionaryWordController;
use App\Http\Controllers\DictionaryInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'dictionary',
    ]);
})->name('health');

Route::get('/dictionary/info', [DictionaryInfoController::class, 'index'])
    ->name('dictionary.info');

Route::prefix('words')->name('words.')->group(function () {
    Route::get('/', [DictionaryWordController::class, 'index'])
        ->name('index');

    Route::get('/{id}', [DictionaryWordController::class, 'show'])
        ->whereNumber('id')
        ->name('show');

    Route::post('/', [DictionaryWordController::class, 'store'])
        ->middleware('dictionary.client')
        ->name('store');
});
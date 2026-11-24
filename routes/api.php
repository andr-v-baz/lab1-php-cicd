
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

 
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/words', [WordController::class, 'index']);
    Route::get('/words/{word}', [WordController::class, 'show']);



    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/words', [WordController::class, 'store']);

        Route::put('/words/{word}', [WordController::class, 'update']);
        Route::patch('/words/{word}', [WordController::class, 'update']);

        
        Route::delete('/words/{word}', [WordController::class, 'destroy']);
    });
});

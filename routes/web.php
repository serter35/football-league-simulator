<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueController;

// Hem Basic Auth hem de Hız Sınırlandırma (Dakikada 60 istek)
Route::middleware(['auth.basic', 'throttle:60,1'])->group(function () {

    // Ana Sayfa
    Route::get('/', [LeagueController::class, 'index'])->name('league.index');

    // İşlem Rotaları (Post işlemleri throttle için daha kritiktir)
    Route::post('/simulate-week', [LeagueController::class, 'simulateWeek']);
    Route::post('/simulate-all', [LeagueController::class, 'simulateAll']);
    Route::post('/reset', [LeagueController::class, 'reset']);
    Route::post('/update-scores', [LeagueController::class, 'updateScores']);
});

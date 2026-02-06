<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueController;

Route::middleware(['auth.basic', 'throttle:60,1'])->name('league.')->group(function () {
    Route::get('/', [LeagueController::class, 'index'])->name('index');
    Route::post('/generate', [LeagueController::class, 'generate'])->name('generate');
    Route::post('/simulate-week/{week}', [LeagueController::class, 'simulateNextWeek'])->name('simulate.week');
    Route::post('/simulate-all', [LeagueController::class, 'simulateAll'])->name('simulate.all');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NationalTeamController;
use App\Http\Controllers\PlayersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    //National Teams
    Route::controller(NationalTeamController::class)->group(function () {
        Route::get('teams', 'index');
        Route::post('import-teams', 'importTeamsFromCsv');
    });

    Route::controller(PlayersController::class)->group(function () {
        //players
        Route::get('players', 'index');
        Route::post('import-players', 'importPlayersFromCsv');
    });
});

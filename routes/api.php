<?php

use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\GroupController;
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
        Route::get('team/{team}', 'getTeamById');
        Route::get('team/{team}/players', 'getPlayersByTeam');
        Route::post('import-teams', 'importTeamsFromCsv');
    });

    Route::controller(PlayersController::class)->group(function () {
        //players
        Route::get('players', 'index');
        Route::get('player/{player}', 'getPlayerById');
        Route::post('import-players', 'importPlayersFromCsv');
    });
    Route::controller(GroupController::class)->group(function () {
        //groups
        Route::get('groups', 'index');
    });
    Route::controller(EstadisticaController::class)->group(function () {
        //groups
        Route::get('fixture', 'getFixture');
        Route::get('fixture/{fixture}', 'getFixtureById');
        Route::get('statistics/{team}', 'getstatisticsTeam');
        Route::get('getPositions', 'getPositions');
    });

});

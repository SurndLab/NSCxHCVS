<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
| 56th Module D - RESTful Album Management API
|
| Public routes: no auth
| User routes:  X-Authorization: Bearer <md5(username)>     [auth.token]
| Admin routes: same token + role=admin                     [auth.token, admin]
*/

// === Public API ===
Route::post('/login', [AuthController::class, 'login']);                       // 1
Route::post('/register', [AuthController::class, 'register']);                 // 2
Route::get('/albums', [AlbumController::class, 'index']);                      // 3
Route::get('/albums/{album}', [AlbumController::class, 'show'])
    ->whereNumber('album');                                                    // 4
Route::get('/albums/{album}/cover', [AlbumController::class, 'cover'])
    ->whereNumber('album');                                                    // 5
Route::get('/albums/{album}/songs', [AlbumController::class, 'songs'])
    ->whereNumber('album');                                                    // 6
Route::get('/songs', [SongController::class, 'index']);                        // 7
Route::get('/songs/{song}/cover', [SongController::class, 'cover'])
    ->whereNumber('song');                                                     // 8

// === User API (auth required) ===
Route::middleware('auth.token')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);                 // 9
    Route::get('/songs/{song}', [SongController::class, 'show'])
        ->whereNumber('song');                                                 // 10
    Route::get('/statistics', [StatisticsController::class, 'index']);         // 11
});

// === Admin API (auth + admin role) ===
Route::middleware(['auth.token', 'admin'])->group(function () {
    // Users
    Route::get('/users', [UserController::class, 'index']);                    // 12
    Route::put('/users/{user}', [UserController::class, 'updateRole'])
        ->whereNumber('user');                                                 // 13
    Route::put('/users/{user}/ban', [UserController::class, 'ban'])
        ->whereNumber('user');                                                 // 14
    Route::put('/users/{user}/unban', [UserController::class, 'unban'])
        ->whereNumber('user');                                                 // 15

    // Albums
    Route::post('/albums', [AlbumController::class, 'store']);                 // 16
    Route::put('/albums/{album}', [AlbumController::class, 'update'])
        ->whereNumber('album');                                                // 17
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])
        ->whereNumber('album');                                                // 18

    // Songs (within album)
    Route::post('/albums/{album}/songs', [SongController::class, 'storeInAlbum'])
        ->whereNumber('album');                                                // 19
    Route::put('/albums/{album}/songs/order', [SongController::class, 'updateOrder'])
        ->whereNumber('album');                                                // 20
    Route::post('/albums/{album}/songs/{song}', [SongController::class, 'updateInAlbum'])
        ->whereNumber('album')->whereNumber('song');                           // 21
    Route::delete('/albums/{album}/songs/{song}', [SongController::class, 'destroyFromAlbum'])
        ->whereNumber('album')->whereNumber('song');                           // 22
});

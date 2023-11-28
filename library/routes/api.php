<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LendingController;

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

//admin férhet hozzá
Route::middleware( ['admin'])->group(function () {
    Route::apiResource('/api/users', UserController::class);
});

//bejelentkezett felhasználó
Route::middleware('auth.basic')->group(function () {
    Route::get('/reservations/{user_id}/{book_id}/{start}', [ReservationController::class, 'show']);
    Route::patch('/reservations/{user_id}/{book_id}/{start}', [ReservationController::class, 'update']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::apiResource('/copies', CopyController::class);
    //lekérdezések
    //with
    Route::get('/with/book_copy', [BookController::class, 'bookCopy']);
    Route::get('/with/lending_user', [LendingController::class, 'lendingUser']);
    Route::get('/with/lending_user2', [LendingController::class, 'lendingUser2']);
    Route::get('/with/copy_book_lending', [CopyController::class, 'copyBookLending']);
    Route::get('/with/user_l_r', [UserController::class, 'userLR']);


});

//bejelentkezés nélkül is hozzáférhet
Route::apiResource('/books', BookController::class);
Route::patch('/user_password/{id}', [UserController::class, 'updatePassword']);
Route::delete('/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'destroy']);
Route::get('/publicated/{book_id}', [BookController::class, 'publicated']);
Route::get('/publicated2/{book_id}', [BookController::class, 'publicated2']);
Route::get('/publicated_count/{book_id}', [BookController::class, 'publicatedCount']);

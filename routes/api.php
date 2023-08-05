<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/me', [AuthController::class, 'myProfile']);

Route::get('dog', [DogController::class, 'findAll']);
Route::get('dog/{id}', [DogController::class, 'findOne']);
Route::get('dog/{id}/remain', [DogController::class, 'findRemains']);
Route::get('dog/{id}/liked', [DogController::class, 'findApproved']);
Route::get('dog/{id}/rejected', [DogController::class, 'findRejected']);

Route::post('dog', [DogController::class, 'create']);
Route::post('dog/{id}/like/{selected}', [DogController::class, 'likeDog']);
Route::post('dog/{id}/reject/{selected}', [DogController::class, 'rejectDog']);
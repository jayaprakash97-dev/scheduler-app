<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchedulerController;
use App\Http\Controllers\Api\AdminAvailabilityController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/slots', [SchedulerController::class, 'getSlots']);
Route::post('/book', [SchedulerController::class, 'bookSlot']);

Route::get('admin/availabilities', [AdminAvailabilityController::class, 'index']);
Route::post('admin/availabilities', [AdminAvailabilityController::class, 'store']);
Route::put('admin/availabilities/{id}', [AdminAvailabilityController::class, 'update']);
Route::delete('admin/availabilities/{id}', [AdminAvailabilityController::class, 'destroy']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

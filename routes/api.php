<?php

use App\Http\Controllers\API\ChildAreaAPIController;
use App\Http\Controllers\API\HotelsAPIController;
use App\Http\Controllers\API\RoomsAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/provinces', function(){
    return view('API.provinces');
});

Route::get('/child-area/{area}', [ChildAreaAPIController::class, 'getChildAreas'])->name('child-area');

Route::prefix('/hotels')->name('hotels.')->group(function(){
    Route::get('/hotels-list', [HotelsAPIController::class, 'index'])->name('hotels-list');
    Route::get('/hotel/{id}', [HotelsAPIController::class, 'show'])->name('hotel');
});

Route::prefix('/rooms-hotel')->name('rooms_hotel.')->group(function(){
    Route::get('/rooms-hotel-list', [RoomsAPIController::class, 'getRoomsHotelList'])->name('rooms-hotel-list');
    Route::get('/room-hotels-for-hotel/{hotel_id}', [RoomsAPIController::class, 'showForHotel'])->name('room-hotels-for-hotel');
    Route::get('/rooms-hotel/{rooms_hotel_id}', [RoomsAPIController::class, 'show'])->name('rooms-hotel');
});

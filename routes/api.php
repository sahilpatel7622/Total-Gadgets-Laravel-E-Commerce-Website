<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register',[ApiController::class,'register']);
Route::get('/login',[ApiController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/list',[ApiController::class,'list']);
    Route::post('/add-data',[ApiController::class,'add_data']);
    Route::delete('/delete-data/{id}',[ApiController::class,'delete_data']);
    Route::get('/search-data/{id}',[ApiController::class,'search_data']);
    Route::put('/update-data/{id}',[ApiController::class,'update_data']);
});

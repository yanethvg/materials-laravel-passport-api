<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitMeasureController;
use App\Http\Controllers\AuthController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// laravel passport
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::group(['prefix' => 'categories', 'middleware' => 'auth:api'], function () {
    // list all categories
    Route::get('/',[CategoryController::class, 'index']);
    // show category
    Route::get('/{id}', [CategoryController::class, 'show']);
    // store category
    Route::post('/', [CategoryController::class, 'store']);
    // update category
    Route::put('/{id}', [CategoryController::class, 'update']);
    // delete category
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::group(['prefix' => 'unitmeasures', 'middleware' => 'auth:api'], function () {
    // list all measures
    Route::get('/',[UnitMeasureController::class, 'index']);
    // show measure
    Route::get('/{id}', [UnitMeasureController::class, 'show']);
    // store measure
    Route::post('/', [UnitMeasureController::class, 'store']);
    // update measure
    Route::put('/{id}', [UnitMeasureController::class, 'update']);
    // delete measure
    Route::delete('/{id}', [UnitMeasureController::class, 'destroy']);
});



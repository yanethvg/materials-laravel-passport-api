<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'categories'], function () {
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



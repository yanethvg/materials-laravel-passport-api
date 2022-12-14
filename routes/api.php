<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UnitMeasureController;
use App\Http\Controllers\AuthController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// laravel passport
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::group(['prefix' => 'categories', 'middleware' => ['auth:api']], function () {
    // paginate categories
    Route::get('/paginate',[CategoryController::class, 'paginate']);
    // show category
    Route::get('/{id}', [CategoryController::class, 'show']);
    // store category
    Route::post('/', [CategoryController::class, 'store']);
    // update category
    Route::put('/{id}', [CategoryController::class, 'update']);
    // delete category
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::group(['prefix' => 'unitmeasures', 'middleware' => ['auth:api','role:boss']], function () {
    // paginate measure
    Route::get('/paginate',[UnitMeasureController::class, 'paginate']);
    // show measure
    Route::get('/{id}', [UnitMeasureController::class, 'show']);
    // store measure
    Route::post('/', [UnitMeasureController::class, 'store']);
    // update measure
    Route::put('/{id}', [UnitMeasureController::class, 'update']);
    // delete measure
    Route::delete('/{id}', [UnitMeasureController::class, 'destroy']);
});

// list all categories
Route::get('categories/',[CategoryController::class, 'index'])->middleware(['auth:api','role:boss|seller']);
// list all measures
Route::get('unitmeasures/',[UnitMeasureController::class, 'index'])->middleware(['auth:api','role:boss|seller']);

Route::group(['prefix' => 'materials', 'middleware' => ['auth:api','role:boss|seller']], function () {
    // list all materials
    Route::get('/',[MaterialController::class, 'index']);
    // show material
    Route::get('/{id}', [MaterialController::class, 'show']);
    // store material
    Route::post('/', [MaterialController::class, 'store']);
    // update material
    Route::put('/{id}', [MaterialController::class, 'update']);
    // delete mmaterial
    Route::delete('/{id}', [MaterialController::class, 'destroy']);
});




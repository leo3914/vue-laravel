<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecipeController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// get all categories
Route::get('categories', [CategoryController::class, 'index']);

// get all recipes
Route::get('recipes', [RecipeController::class, 'index']);
// single recipe
Route::get('recipes/{recipe}', [RecipeController::class, 'show']);
// delete single recipe
Route::delete('recipes/{recipe}', [RecipeController::class, 'destroy']);
// store a recipe
Route::post('recipes', [RecipeController::class, 'store']);
// update recipe
Route::patch('recipes/{recipe}', [RecipeController::class, 'update']);
// upload api
Route::post('recipes/upload', [RecipeController::class, 'upload']);

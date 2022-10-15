<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\JWTAuthController;
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

Route::prefix('articles')->middleware('jwt.verify')->controller(ArticleController::class)->group(function(){
    Route::get('/', 'listArticle');
    Route::post('/create', 'createArticle');
    Route::post('/update', 'updateArticle');
    Route::delete('/delete/{id}', 'deleteArticle');
});


//Routes for authentifications
Route::prefix('auth')->controller(JWTAuthController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/profile', 'profile');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
});

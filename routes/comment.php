<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddFriendController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * 
 */

Route::group(['middleware'=>"checktoken"],function()
{
    Route::post('comment',[CommentController::class, 'comment']);

    Route::post('commentdelete',[CommentController::class, 'commentDelete']);

    Route::post('commentupdate',[CommentController::class, 'commentupdate']);
});
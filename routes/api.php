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
Route::post('Signup',[UserController::class, 'signUp']);

Route::post('login',[UserController::class, 'login']);

Route::post('ForgetPasswordMail',[UserController::class, 'forgetPassword']);

Route::post('ChangePassword',[UserController::class, 'changePassword']);

Route::get('verfi/email/123/ver/{mail}/{token}',[UserController::class, 'Verification']);


Route::group(['middleware'=>"checktoken"],function()
{
    Route::post('update',[UserController::class, 'update']);

    Route::post('logout',[UserController::class, 'logout']);

    Route::post('userpostscomments', [UserController::class, 'user_details_and_posts_details']);

    Route::post('addfriend',[AddFriendController::class, 'add_friend']);

    Route::post('removefriend',[AddFriendController::class, 'removeFriend']);

    Route::post('post',[PostController::class, 'post']);

    Route::post('postupdate',[PostController::class, 'postupdate']);

    Route::post('postdelete',[PostController::class, 'postdelete']);

    Route::post('postread',[PostController::class, 'read']);

    Route::post('comment',[CommentController::class, 'comment']);

    Route::post('commentdelete',[CommentController::class, 'commentDelete']);

    Route::post('commentupdate',[CommentController::class, 'commentupdate']);
});
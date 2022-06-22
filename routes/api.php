<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Main\FavoriteController;
use App\Http\Controllers\Main\ForumCommentController;
use App\Http\Controllers\Main\ForumController;
use App\Http\Controllers\Main\KeywordController;
use App\Http\Controllers\Main\MasjidController;
use App\Http\Controllers\Main\MasjidReviewController;
use App\Http\Controllers\Main\OperatingHourController;
use App\Http\Controllers\Main\PrayerTimeController;
use App\Http\Controllers\Main\ProductController;
use App\Http\Controllers\Main\ProductInformationController;
use App\Http\Controllers\Main\RestoranController;
use App\Http\Controllers\Main\RestoranReviewController;
use App\Models\ForumComment;

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

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'code' => 404,
        'message' => 'Route Not Found', 
    ]);
});


Route::prefix('v1')->group(function (){
    Route::prefix('users')->group(function (){
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });
});

Route::middleware('jwt.verify')->group(function (){
    Route::prefix('v1')->group(function (){
        Route::prefix('users')->group(function (){
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refreshToken', [AuthController::class, 'refresh']);

            Route::prefix('me')->group(function () {
                Route::get('profile', [AuthController::class, 'userProfile']);
                Route::post('editProfile', [AuthController::class, 'editProfile']);
                Route::put('updateUserPassword', [AuthController::class, 'updateUserPassword']);
                Route::post('uploadPhoto', [AuthController::class, 'uploadProfilePicture']);
            });
        });

        Route::prefix('masjids')->group(function(){
            Route::post('create', [MasjidController::class, 'store']);
            Route::get('showAll', [MasjidController::class, 'show']);
            Route::get('photos/{masjidId}', [MasjidController::class, 'getMasjidPhoto']);
            Route::get('/byType/{typeId}', [MasjidController::class, 'getByType']);
            Route::get('{id}', [MasjidController::class, 'index']);
            Route::post('edit/{id}', [MasjidController::class, 'update']);
            Route::delete('delete', [MasjidController::class, 'destroy']);
        });

        Route::get('prayTime/{city}', [PrayerTimeController::class, 'getPrayTime']);

        Route::prefix('reviewMasjid')->group(function (){
            Route::post('store/{masjidId}', [MasjidReviewController::class, 'store']);
            Route::get('/{masjidId}', [MasjidReviewController::class, 'show']);
            Route::delete('deleteReview/{reviewId}', [MasjidReviewController::class, 'destroy']);
            Route::post('uploadImage/{reviewId}', [MasjidReviewController::class, 'uploadImage']);
        });

        Route::prefix('keyword')->group(function (){
            Route::post('store', [KeywordController::class, 'store']);
            Route::get('all', [KeywordController::class, 'index']);
            Route::get('detail/{keywordId}', [KeywordController::class, 'show']);
            Route::put('update/{keywordId}', [KeywordController::class, 'update']);
            Route::delete('delete/{keywordId}', [KeywordController::class, 'destroy']);
        });

        Route::prefix('products')->group(function (){
            Route::post('store', [ProductController::class, 'store']);
            Route::get('all', [ProductController::class, 'index']);
            Route::get('detail/{productId}', [ProductController::class, 'show']);
            Route::post('update/{productId}', [ProductController::class, 'update']);
            Route::delete('delete/{productId}', [ProductController::class, 'destroy']);
        });

        Route::prefix('productInformation')->group(function(){
            Route::post('store', [ProductInformationController::class,'store' ]);
            Route::get('all', [ProductInformationController::class, 'index']);
            Route::get('detail/{informationId}', [ProductInformationController::class, 'show']);
            Route::put('update/{informationId}', [ProductInformationController::class, 'update']);
            Route::delete('delete/{informationId}', [ProductInformationController::class, 'destroy']);
        });

        Route::prefix('forums')->group(function (){
            Route::post('store', [ForumController::class, 'store']);
            Route::get('all', [ForumController::class, 'index']);
            Route::get('detailForum/{forumId}', [ForumController::class, 'show']);
            Route::put('update/{forumId}', [ForumController::class, 'update']);
            Route::delete('delete/{forumId}', [ForumController::class, 'destroy']);
            Route::post('like/{forumId}', [ForumController::class, 'likeForum']);
        });

        Route::prefix('comments')->group(function (){
            Route::post('store', [ForumCommentController::class, 'store']);
            Route::get('all', [ForumCommentController::class, 'index']);
            Route::get('detailComment/{commentId}', [ForumCommentController::class, 'show']);
            Route::put('update/{commentId}', [ForumCommentController::class, 'update']);
            Route::delete('delete/{commentId}', [ForumCommentController::class, 'destroy']);
            Route::post('like/{commentId}', [ForumCommentController::class, 'likeComment']);
        });

        Route::prefix('restoran')->group(function(){
            Route::post('store', [RestoranController::class, 'store']);
            Route::post('addFavorite/{resoId}', [RestoranController::class, 'addFavorite']);
            Route::post('operatingHour/{restoId}', [OperatingHourController::class, 'store']);
            Route::get('all', [RestoranController::class, 'index']);
            Route::get('allTypeFood', [RestoranController::class, 'getTypeFood']);
            Route::get('all/byFoodType', [RestoranController::class, 'sortByFoodType']);
            Route::get('all/byCertification', [RestoranController::class, 'sortByCertification']);
            Route::get('detailResto/{restoId}', [RestoranController::class, 'show']);
            Route::get('photos/{restoId}', [RestoranController::class, 'getRestoPhotos']);
            Route::post('update/{restoId}', [RestoranController::class, 'update']);
            Route::delete('delete/{restoId}', [RestoranController::class, 'destroy']);
        });

        Route::prefix('favorites')->group(function(){
            Route::post('addResto/{restoId}', [FavoriteController::class, 'addResto']);
            Route::post('addMasjid/{masjid}', [FavoriteController::class, 'addMasjid']);
            Route::get('/', [FavoriteController::class, 'getAllFavorite']);
            Route::delete('/deleteResto/{favId}', [FavoriteController::class, 'deleteResto']);
            Route::delete('/deleteMasjid/{masjid}', [FavoriteController::class, 'deleteMasjid']);
        });

        Route::prefix('reviewResto')->group(function(){
            Route::post('store/{restoId}', [RestoranReviewController::class, 'store']);
            Route::get('allReview/{restoId}', [RestoranReviewController::class, 'show']);
            Route::delete('deleteReview/{reviewId}', [RestoranReviewController::class, 'destroy']);
        });
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Main\KeywordController;
use App\Http\Controllers\Main\MasjidController;
use App\Http\Controllers\Main\MasjidReviewController;
use App\Http\Controllers\Main\PrayerTimeController;
use App\Http\Controllers\Main\ProductController;
use App\Http\Controllers\Main\ProductInformationController;
use App\Http\Controllers\Main\QiblaController;

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

Route::middleware('api')->group(function (){
    Route::prefix('v1')->group(function (){
        Route::prefix('users')->group(function (){
            Route::post('register', [AuthController::class, 'register']);
            Route::post('login', [AuthController::class, 'login']);
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
            Route::get('{id}', [MasjidController::class, 'index']);
            Route::post('edit/{id}', [MasjidController::class, 'update']);
            Route::delete('delete', [MasjidController::class, 'destroy']);
        });

        Route::get('prayTime/{city}', [PrayerTimeController::class, 'getPrayTime']);

        Route::prefix('reviewMasjid')->group(function (){
            Route::post('store/{masjidId}', [MasjidReviewController::class, 'store']);
            Route::get('getAll', [MasjidReviewController::class, 'index']);
            Route::get('reviewDetail/{reviewId}', [MasjidReviewController::class, 'show']);
            Route::post('updateReview/{reviewId}', [MasjidReviewController::class, 'update']);
            Route::delete('deleteReview/{reviewId}', [MasjidReviewController::class, 'destroy']);
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
    });
});

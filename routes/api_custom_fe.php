<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Main\MasjidReviewController;
use App\Http\Controllers\Fe\FeProductController;
use App\Http\Controllers\Fe\FeMasjidController;
use App\Http\Controllers\Fe\FeRestoController;
use App\Http\Controllers\Fe\FeForumController;
use App\Http\Controllers\Fe\FeOrderCartController;


Route::middleware('api')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::prefix("fe")->group(function () {
            Route::prefix('users')->group(function () {
                Route::prefix('me')->group(function () {
                    Route::get('profile', [AuthController::class, 'userProfile']);
                });
            });


            Route::prefix('masjids')->group(function () {
                Route::get('{id}/photos', [FeMasjidController::class, 'getMasjidPhoto']);
                Route::get('{id}/reviews', [FeMasjidController::class, 'getMasjidReviews']);
            });

            Route::get('prayTime/{city}', [PrayerTimeController::class, 'getPrayTime']);

            Route::prefix('reviewMasjid')->group(function () {
                Route::post('store/{masjidId}', [MasjidReviewController::class, 'store']);
            });

            Route::prefix('keyword')->group(function () {
                Route::post('store', [KeywordController::class, 'store']);
                Route::get('all', [KeywordController::class, 'index']);
                Route::get('detail/{keywordId}', [KeywordController::class, 'show']);
                Route::put('update/{keywordId}', [KeywordController::class, 'update']);
                Route::delete('delete/{keywordId}', [KeywordController::class, 'destroy']);
            });

            Route::prefix('forums')->group(function () {
                Route::get('category', [FeForumController::class, 'getForumCategory']);
                Route::get('all-paginate', [FeForumController::class, 'getForumPaginate']);
                Route::post('like/{forumId}', [FeForumController::class, 'likeForum']);
                Route::post('unlike/{forumId}', [FeForumController::class, 'unlikeForum']);
                Route::get('comment/{forumId}', [FeForumController::class, 'getComment']);
            });


            Route::prefix('comments')->group(function () {
                Route::post('like/{commentId}', [FeForumController::class, 'likeComment']);
                Route::post('unlike/{commentId}', [FeForumController::class, 'unlikeComment']);
            });

            Route::prefix('products')->group(function () {
                Route::get('category', [FeProductController::class, 'getProductCategory']);
                Route::get('get-by-category/{id}', [FeProductController::class, 'getByCategory']);
            });

            Route::prefix('restoran')->group(function () {
                Route::get('me', [FeRestoController::class, 'myResto']);
                Route::get('cert', [FeRestoController::class, 'getAllCert']);
                Route::get('all-raw', [FeRestoController::class, 'all-raw']);
                Route::get('{id}/detail', [FeRestoController::class, 'getDetailRestaurant']);
                Route::get('{id}/food-category', [FeRestoController::class, 'getAllFoodCategoryOnResto']);
                Route::post('{id}/food-category', [FeRestoController::class, 'storeRestaurantCategory']);
                Route::get('food/category/{id}', [FeRestoController::class, 'getFoodRestaurantByCategory']);
                Route::get('{id}/food', [FeRestoController::class, 'getAllFoodOnResto']);
                Route::get('cert', [FeRestoController::class, 'getCertif']);
                Route::get('food-type', [FeRestoController::class, 'getFoodType']);
                Route::get('nearest', [FeRestoController::class, 'getNearestRestaurant']);

                Route::get('certification/{id}', [FeRestoController::class, 'getBasedCertif']);
                Route::get('{id}/reviews', [FeRestoController::class, 'getReviews']);

                Route::get('{id}/getAllOrders', [FeOrderCartController::class, 'orderByResto']);


                Route::prefix("{id}/update")->group(function () {
                    Route::post('cert', [FeRestoController::class, 'updateRestoCert']);
                    Route::post('resto-type', [FeRestoController::class, 'updateRestoType']);
                    Route::post('address', [FeRestoController::class, 'updateAddress']);
                    Route::post('phone', [FeRestoController::class, 'updatePhone']);
                });

                Route::prefix('reviewResto')->group(function () {
                    Route::post('store/{masjidId}', [FeRestoController::class, 'store']);
                });
            });

            Route::prefix('orders')->group(function () {
                Route::prefix('carts')->group(function () {
                    Route::get('status', [FeRestoController::class, 'getAllOrderStatus']);

                    Route::middleware('auth.role:1,2,3,4,5')->group(function () {
                        Route::post('createCart/{restoId}', [FeOrderCartController::class, 'createCart']);
                    });

                    Route::post('createCart/{restoId}', [FeOrderCartController::class, 'createCart']);
                });
            });

        });
    });
});

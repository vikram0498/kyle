<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\LoginRegisterController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\SocialMediaController;
use App\Http\Controllers\Api\User\HomeController;
use App\Http\Controllers\Api\User\BuyerController;
use App\Http\Controllers\Api\User\ProfileController;


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

Route::controller(LoginRegisterController::class)->group(function(){

    Route::post('register', 'register');

    Route::post('login', 'login');

    Route::post('forgot-password', 'forgotPassword');

    Route::post('reset-password', 'resetPassword');

    Route::get('/email/verify/{id}/{hash}', 'verifyEmail');

});

Route::controller(SocialMediaController::class)->group(function(){

    Route::post('handle-google', 'handleGoogle');

    Route::post('handle-facebook', 'handleFacebook');

});

Route::middleware('auth:sanctum')->group(function () { 

    Route::post('logout', [LogoutController::class, 'logout']);
    
    Route::get('user-details', [ProfileController::class, 'userDetails']);

    Route::post('upload-single-buyer-details', [BuyerController::class, 'uploadSingleBuyerDetails']);

    Route::post('upload-multiple-buyers-csv', [BuyerController::class, 'import']);

    Route::post('buy-box-search/{page?}', [BuyerController::class, 'buyBoxSearch']);

    Route::get('fetch-buyers/{page?}', [BuyerController::class, 'fetchBuyers']);

    Route::get('getPropertyTypes', [BuyerController::class, 'getPropertyTypes']);

    Route::get('getBuildingClassNames', [BuyerController::class, 'getBuildingClassNames']);

    Route::get('getPurchaseMethods', [BuyerController::class, 'getPurchaseMethods']);

    Route::get('getParkings', [BuyerController::class, 'getParkings']);

    Route::get('getLocationFlaws', [BuyerController::class, 'getLocationFlaws']);

    Route::get('single-buyer-form-details', [BuyerController::class, 'singleBuyerFormElementValues']);

    Route::get('getCountries', [BuyerController::class, 'getCountries']);

    Route::post('getStates', [BuyerController::class, 'getStates']);

    Route::post('getCities', [BuyerController::class, 'getCities']);

    Route::post('update-profile', [ProfileController::class, 'updateProfile']);

});

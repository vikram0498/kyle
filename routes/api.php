<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\LoginRegisterController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\SocialMediaController;
use App\Http\Controllers\Api\User\HomeController;
use App\Http\Controllers\Api\User\BuyerController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\PaymentController;
use App\Http\Controllers\Api\User\SupportController;
use App\Http\Controllers\Api\User\CommanController;
use App\Http\Controllers\Api\User\SearchBuyerController;
use App\Http\Controllers\Api\User\CopyBuyerController;
use App\Http\Controllers\Api\User\TwilioController;
use App\Http\Controllers\Api\User\BuyerVerificationController;




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

    Route::post('verify-set-password', 'verifyBuyerEmailAndSetPassword');

    Route::get('get-email/{id}', 'getEmail');
    
});

Route::controller(SocialMediaController::class)->group(function(){

    Route::post('handle-google', 'handleGoogle');

    Route::post('handle-facebook', 'handleFacebook');

});

// Start Comman API
Route::post('getCities', [CommanController::class, 'getCities']);
// End Comman API

Route::get('single-buyer-form-details', [BuyerController::class, 'singleBuyerFormElementValues']);

Route::get('search-buyer-form-details', [SearchBuyerController::class, 'searchBuyerFormElementValues']);

Route::get('copy-buyer-form-details', [CopyBuyerController::class, 'copyBuyerFormElementValues']);

Route::post('copy-single-buyer-details/{token}', [CopyBuyerController::class, 'uploadCopyBuyerDetails']);

Route::get('check-token/{token}', [CopyBuyerController::class, 'isValidateToken']);

Route::get('get-contact-preferance', [SupportController::class, 'getContactPreferance']);

Route::post('/support', [SupportController::class, 'support']);

Route::group(['middleware' => ['api','auth:sanctum']],function () { 

    Route::post('logout', [LogoutController::class, 'logout']);
    
    Route::get('user-details', [ProfileController::class, 'userDetails']);

    Route::post('update-profile', [ProfileController::class, 'updateProfile']);

    Route::get('last-form-step', [BuyerVerificationController::class, 'getLastVerificationForm']);

    Route::post('buyer-profile-verification', [BuyerVerificationController::class, 'index']);
   
    Route::post('send-sms', [TwilioController::class, 'sendSms']);

    Route::post('buy-box-search/{page?}', [SearchBuyerController::class, 'buyBoxSearch']);
    Route::get('get-last-search', [SearchBuyerController::class, 'lastSearchByUser']);
    Route::post('last-search-buyer', [SearchBuyerController::class, 'lastSearchBuyers']);

    Route::get('copy-single-buyer-form-link', [CopyBuyerController::class, 'copySingleBuyerFormLink']);

    Route::post('upload-single-buyer-details', [BuyerController::class, 'uploadSingleBuyerDetails']);

    Route::post('upload-multiple-buyers-csv', [BuyerController::class, 'import']);

    Route::get('fetch-buyers/{page?}', [BuyerController::class, 'fetchBuyers']);


    Route::post('red-flag-buyer', [BuyerController::class, 'redFlagBuyer']);

    Route::post('unhide-buyer', [BuyerController::class, 'unhideBuyer']);

    Route::post('like-unlike-buyer', [BuyerController::class, 'storeBuyerLikeOrUnlike']);  

    Route::delete('del-like-unlike-buyer/{user_id}/{buyer_id}', [BuyerController::class, 'deleteBuyerLikeOrUnlike']);

    
    Route::post('my-buyers', [BuyerController::class, 'myBuyersList']);

    Route::get('search-address', [BuyerController::class, 'searchAddress']);

    Route::get('getPlans', [HomeController::class, 'getPlans']);

    Route::get('getAddtionalCredits', [HomeController::class, 'getAdditionalCredits']);

    Route::get('getVideo/{key}', [HomeController::class, 'getVideo']);

    Route::get('config', [PaymentController::class, 'config']);
 
    Route::post('/checkout-session', [PaymentController::class, 'createCheckoutSession']);

    Route::post('/buyer-make-payment', [PaymentController::class, 'createBuyerPaymentIntent']);

    Route::post('/checkout-success', [PaymentController::class, 'checkoutSuccess']);

    Route::post('/payment-history', [PaymentController::class, 'paymentHistory']);

    Route::get('/get-current-limit', [ProfileController::class, 'getCurrentLimit']);


});

Route::post('/stripe/webhook', [PaymentController::class, 'handleStripeWebhook']);

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return redirect()->route('auth.login');
});
// list storage
/* Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return '<h1>Storage linked</h1>';
}); */

//Clear Cache facade value:
Route::get('/cache-clear', function() {
    Artisan::call('optimize:clear');
    return '<h1>All Cache cleared</h1>';
});

Route::get('/send-test-mail', function() {
    $recipient = 'rakeshjonwal.his@gmail.com';
    $subject = 'Subject of the email';
    $message = 'This is a simple text email.';
    
    try{
        Mail::raw($message, function($mail) use ($recipient, $subject) {
            $mail->to($recipient)
            ->subject($subject);
        });
        
        return '<h1>Mail Sent Successfully!</h1>';
    } catch(\Exception $e){
        dd($e->getMessage().'->'.$e->getLine());
    }
});

Route::get('/phpinfo',function(){
	echo phpinfo();
});

// Auth::routes(['verify' => true]);

Route::post('get-document-verification-status', [HomeController::class,'apiVerificationStatus'])->name('apiVerificationStatus');

Route::get('get-latest-kyc-count', [HomeController::class,'getCountOfLatestKyc'])->name('getCountOfLatestKyc');


Route::get('email/verify/{id}/{hash}', [VerificationController::class,'verify'])->name('verification.verify');

Route::group(['middleware' => ['web', 'guest'], 'as' => 'auth.','prefix'=>''], function () {    
    // Route::view('signup', 'auth.admin.register')->name('register');
    Route::view('/login', 'auth.admin.login')->name('login');
    Route::view('forget-password', 'auth.admin.forget-password')->name('forget-password');
    Route::view('reset-password/{token}/{email}', 'auth.admin.reset-password')->name('reset-password');
 
});    

Route::group(['middleware' => ['auth','preventBackHistory']], function () {
    Route::view('profile', 'auth.profile.index')->name('auth.admin-profile');
    Route::group(['as' => 'admin.','prefix'=>''], function () {        
        Route::view('dashboard', 'admin.index')->name('dashboard');
        Route::view('plan', 'admin.plan.index')->name('plan');
        Route::view('addon', 'admin.addon.index')->name('addon');
        Route::view('settings', 'admin.setting.index')->name('settings');
        Route::view('users', 'admin.seller.index')->name('seller');
        Route::view('deleted-users', 'admin.deleted-users.index')->name('deleted-users');
        Route::view('buyer', 'admin.buyer.index')->name('buyer');    
        Route::view('buyer-verification', 'admin.buyer.new-kyc')->name('buyer-verification');    
        Route::view('deleted-buyers', 'admin.deleted-buyer-users.index')->name('deleted-buyers');        
        Route::view('buyer/import', 'admin.buyer.import-buyers')->name('import-buyers');
        Route::view('supports', 'admin.support.index')->name('supports');

        Route::view('invited-list', 'admin.buyer-invitations.index')->name('buyer-invited-list');

        Route::view('search-log', 'admin.search-log.index')->name('search-log');

        Route::view('search-buyer', 'admin.buyer.search-buyer')->name('search-buyer-form');

        Route::view('transactions', 'admin.transactions.index')->name('transactions'); 

        Route::view('buyer-transactions', 'admin.buyer-transactions.index')->name('buyer-transactions'); 
        
        Route::view('profile-tags', 'admin.buyer-plans.index')->name('buyer-plans');    

        Route::view('campaigns', 'admin.campaigns.index')->name('campaigns');
        
        Route::view('ad-banner', 'admin.advertise-banner.index')->name('ad-banner');

        Route::view('chat-reports', 'admin.chat-reports.index')->name('chat-reports');


    });
});

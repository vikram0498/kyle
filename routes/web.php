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


Route::get('/', function () {
    return redirect('login');
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

// Auth::routes(['verify' => true]);

Route::get('email/verify/{id}/{hash}', [VerificationController::class,'verify'])->name('verification.verify');

Route::group(['middleware' => ['web', 'guest'], 'as' => 'auth.','prefix'=>''], function () {    
    // Route::view('signup', 'auth.admin.register')->name('register');
    Route::view('login', 'auth.admin.login')->name('login');
    Route::view('forget-password', 'auth.admin.forget-password')->name('forget-password');
    Route::view('reset-password/{token}/{email}', 'auth.admin.reset-password')->name('reset-password');
 
});    

Route::group(['middleware' => ['auth','preventBackHistory']], function () {
    Route::view('admin/profile', 'auth.profile.index')->name('auth.admin-profile');
    Route::group(['as' => 'admin.','prefix'=>'admin'], function () {        
        Route::view('dashboard', 'admin.index')->name('dashboard');
        Route::view('plan', 'admin.plan.index')->name('plan');
        Route::view('video', 'admin.video.index')->name('video');
        Route::view('addon', 'admin.addon.index')->name('addon');
        Route::view('setting', 'admin.setting.index')->name('setting');
        Route::view('users', 'admin.seller.index')->name('seller');
        Route::view('deleted', 'admin.deleted-users.index')->name('deleted-users');
        Route::view('buyer', 'admin.buyer.index')->name('buyer');        
        Route::view('buyer/import', 'admin.buyer.import-buyers')->name('import-buyers');
        Route::view('supports', 'admin.support.index')->name('supports');

        Route::view('search-log', 'admin.search-log.index')->name('search-log');

        Route::view('search-buyer', 'admin.buyer.search-buyer')->name('search-buyer-form');

        Route::view('transactions', 'admin.transactions.index')->name('transactions'); 

        Route::view('buyer-transactions', 'admin.transactions.index')->name('buyer-transactions'); 
        
        Route::view('buyer-plans', 'admin.buyer-plans.index')->name('buyer-plans');    
        
        

    });
});
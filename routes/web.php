<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChangePasswordController;

// accounting transaction controller
use App\Http\Controllers\Admin\accounting_transaction\CapitalInvestment;



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

/*
* Admin Panel Routes
*/
// Auth::routes();
Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
});

Route::get('/' , [HomeController::class,'index']);
Route::get('/home' , [HomeController::class,'index']);
Route::get('/aboutUs' , [HomeController::class,'aboutUs']);
Route::get('/donationProcess' , [HomeController::class,'donationProcess']);
Route::post('/donationFormAction' , [HomeController::class,'donationFormAction'])->name('donationFormAction');
Route::get('/sendSms' , [HomeController::class,'sendSms'])->name('sendSms');


Route::any('/admin', function(){
    return redirect()->route('admin.dashboard');
});


Route::group(['prefix'=>'admin', 'middleware'=>'auth', 'namespace'=>'Admin'], function() {

    Route::get('dashboard' , [DashboardController::class,'index'])->name('admin.dashboard');

    // accounting transaction
    Route::group(['prefix'=>'accounting_transaction'],function(){
        /* capital investment */
        Route::get('capital_investment' , [CapitalInvestment::class,'index'])->name('accounting_transaction.capital_investment');
        Route::get('capital_investment/create', [CapitalInvestment::class,'create'])->name('accounting_transaction.capital_investment.create');
        Route::post('capital_investment/store', [CapitalInvestment::class, 'store'])->name('accounting_transaction.capital_investment.store');
        Route::get('capital_investment/{id}/edit', [CapitalInvestment::class, 'edit'])->name('accounting_transaction.capital_investment.edit');
        Route::post('capital_investment/{id}', [CapitalInvestment::class, 'update'])->name('accounting_transaction.capital_investment.update');
        Route::get('capital_investment/{id}', [CapitalInvestment::class, 'show'])->name('accounting_transaction.capital_investment.show');
        Route::delete('capital_investment/{id}', [CapitalInvestment::class, 'destroy'])->name('accounting_transaction.capital_investment.delete');
    });

    // Donation Management
    Route::group(['prefix'=>'donation'],function(){
        Route::get('donationRecord' , [DonationController::class,'index'])->name('donation.donationRecord');
        Route::post('singleDonationInfo' , [DonationController::class,'singleDonationInfo'])->name('donation.singleDonationInfo');
        Route::post('updateDonation', [DonationController::class, 'update'])->name('donation.updateDonation');
    });

    // User Management
    Route::group(['prefix'=>'user'],function(){
        Route::get('userRecord' , [UserController::class,'index'])->name('user.userRecord');
        Route::post('store' , [UserController::class,'store'])->name('user.store');
        Route::get('edit/{id}' ,  [UserController::class,'edit'])->name('user.edit');
        Route::get('create' ,  [UserController::class,'create'])->name('user.create');
        Route::post('destroy' ,  [UserController::class,'destroy'])->name('user.destroy');
        Route::post('update', [UserController::class, 'update'])->name('user.update');
    });

    Route::group(['prefix'=>'userPass'],function(){
        Route::get('index', [ChangePasswordController::class,'index'])->name('userPass.index');
        Route::post('update', [ChangePasswordController::class,'update'])->name('userPass.update');

    });






    // profile routes
    Route::get('/profile/{id}',[ProfileController::class,'show'])->name('profile.show');
    Route::get('/profile',[ProfileController::class,'index'])->name('profile');
    Route::post('/profile/update',[ProfileController::class,'update'])->name('profile.update');
    Route::post('/users/profile-photo',[ProfileController::class,'profilePhoto'])->name('users.profile-photo');
    Route::get('/user-access/permissions/{id}',[UserAccessController::class,'user_permissions'])->name('user_permissions');
    Route::post('/user-access/toggle', [UserAccessController::class,'toggleStatus'])->name('user-access.toggle');
    Route::post('/users/toggle', [UserAccessController::class,'toggleStatus'])->name('users.toggle');


    Route::any('/{$any}', function(){
        return redirect()->route('admin.dashboard');
    });

});

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
   // Artisan::call('view:clear');
    return "Cleared!";
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

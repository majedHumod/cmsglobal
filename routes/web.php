<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
//use App\Models\Role; 
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SmsController;



Route::get('/', function () {
    return view('welcome');
});



Route::middleware([
    'auth:sanctum',config('jetstream.auth_session'),'verified','tenants'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
     // Notes routes with admin role middleware

   //  Route::resource('/notes', NoteController::class);

  Route::middleware(['auth', 'role:user|admin'])->group(function () {
    Route::resource('/notes', NoteController::class);
});

// Articles routes - admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/articles', ArticleController::class);
});


});

Route::get('/send-sms', [SmsController::class, 'sendTestSms']);

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
});


//Route::resource('/notes', NoteController::class);


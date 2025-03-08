<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
//use App\Models\Role; 
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SmsController;



Route::get('/', function () {
    return view('welcome');
});

Session::put('locale', 'en');
App::setLocale('en');



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
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


//Route::resource('/notes', NoteController::class);


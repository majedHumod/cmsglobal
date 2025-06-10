<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
//use App\Models\Role; 
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\PageController;



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

// Meal Plans routes - accessible to both admin and user
Route::middleware(['auth', 'role:user|admin'])->group(function () {
    Route::resource('/meal-plans', MealPlanController::class);
});

// Public meal plans route (accessible to all authenticated users)
Route::get('/meal-plans-public', [MealPlanController::class, 'publicIndex'])->name('meal-plans.public');

// Pages routes - accessible to admin and page_manager
Route::middleware(['auth', 'role:admin|page_manager'])->group(function () {
    Route::resource('/pages', PageController::class);
});

// Public pages route (accessible to all authenticated users)
Route::get('/pages-public', [PageController::class, 'publicIndex'])->name('pages.public');

});

// Public page view route (accessible to everyone)
Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/send-sms', [SmsController::class, 'sendTestSms']);

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
});


//Route::resource('/notes', NoteController::class);
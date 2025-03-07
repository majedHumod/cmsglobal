<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
//use App\Models\Role; 

Route::get('/', function () {
    return view('welcome');
});

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



  Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/notes', NoteController::class);
});




});

//Route::resource('/notes', NoteController::class);


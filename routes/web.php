<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified']) // Применяем middleware к маршруту контроллера
    ->name('dashboard');

// Route::view('add-post', 'add-new-post')->name('add-post');
Route::get('add-post', [PostController::class, 'create'])->name('add-post');
Route::post('store-post', [PostController::class, 'store'])->name('store-post'); // добавили



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

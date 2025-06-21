<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () { // <-- Здесь применяется middleware 'auth' ко всей группе

    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');

    Route::get('add-post', [PostController::class, 'create'])->name('add-post');
    Route::post('store-post', [PostController::class, 'store'])->name('store-post');

    Route::get('edit-post/{id}', [PostController::class, 'edit'])->name('edit-post');
    Route::put('update-post/{id}', [PostController::class, 'update'])->name('update-post');


    Route::post('restore-post/{id}', [PostController::class, 'restore'])->name('restore-post');
    Route::delete('delete-post/{id}', [PostController::class, 'destroy'])->name('delete-post');
    Route::delete('force-delete-post/{id}', [PostController::class, 'forceDelete'])->name('force-delete-post');

    Route::resource('roles', RoleController::class);  // Все методы в одном маршруте (resource)


    // Маршруты ниже создаются автоматически в Laravel starter kits (например, Breeze или Jetstream), 
    // предоставляя базовое управление профилем пользователя и аутентификацию.
    // Пока их не трогаем. Это другое меню profile, которое мы не используем.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';  // <-- Здесь загружаются маршруты аутентификации Breeze, включая логин/регистрацию


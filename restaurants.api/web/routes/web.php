<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/show', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['middleware' => ['role:super_admin']], function () {
//        Route::resource('restaurants', RestaurantController::class);
    });

    Route::group(['middleware' => ['role:super_admin,restaurant_admin']], function () {
        Route::post('/upload', UploadFileController::class)->name('file.upload');
    });
});

require __DIR__.'/auth.php';

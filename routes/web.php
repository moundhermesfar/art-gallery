<?php

use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download-image', [ImageController::class, 'download'])->name('image.download');
Route::post('/toggle-favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

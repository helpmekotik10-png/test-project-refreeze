<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/group/{id}', [CatalogController::class, 'showGroup'])->name('group.show');
Route::get('/product/{id}', [ProductController::class, 'showProduct'])->name('product.show');
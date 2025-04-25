<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;


Route::get('/',[HomeController::class,'index']);


Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

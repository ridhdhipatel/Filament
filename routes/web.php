<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index']);
Route::post('create/quote',[HomeController::class,'create_quote'])->name('create.route');

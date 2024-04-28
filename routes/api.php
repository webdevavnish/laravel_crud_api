<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminSanctumController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::resource('/admin',AdminController::class);

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('/admin',AdminController::class);
});

Route::post('/login',[AdminSanctumController::class,'login'])->name('admin.login');

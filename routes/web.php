<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DeparmentController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DeviceController;
use App\Http\Controllers\admin\UnitController;
use App\Http\Controllers\admin\SupplierController;


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('departments', DeparmentController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('devices', DeviceController::class);
        Route::resource('units', UnitController::class);
        Route::resource('suppliers', SupplierController::class);
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});
require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DeparmentController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DeviceController;
use App\Http\Controllers\admin\UnitController;
use App\Http\Controllers\admin\SupplierController;
use App\Http\Controllers\admin\DeviceItemController;
use App\Http\Controllers\admin\BorrowController;


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
        Route::post('/device-items/store', [DeviceItemController::class, 'store'])->name('device-items.store');
        Route::put('/device-items/{id}', [DeviceItemController::class, 'update'])->name('device-items.update');
        Route::delete('/device-items/{id}', [DeviceItemController::class, 'destroy'])->name('device-items.destroy');
        Route::resource('borrows', BorrowController::class);
        Route::get('/borrows/device-items/{device_id}', [BorrowController::class, 'getDeviceItems']);
        Route::post('borrows/{id}/approve', [BorrowController::class, 'approve'])->name('borrows.approve');
        Route::post('borrows/{id}/return', [BorrowController::class, 'markReturned'])->name('borrows.return');
        Route::post('borrows/{id}/cancel', [BorrowController::class, 'cancel'])->name('borrows.cancel');
        Route::get('borrows/{id}/details', [BorrowController::class, 'getBorrowDetails'])->name('borrows.details');
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});
require __DIR__ . '/auth.php';

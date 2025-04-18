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
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MaintenanceController;


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('departments', DeparmentController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('devices', DeviceController::class);
        Route::resource('units', UnitController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('/device-items/{device_id}', [DeviceItemController::class, 'index'])->name('get-device-items');
        Route::get('/device-items/create', [DeviceItemController::class, 'create'])->name('device-items.create');
        Route::get('/device-items/{id}/edit', [DeviceItemController::class, 'edit'])->name('device-items.edit');
        Route::get('/device-items/{device_id}/json', [DeviceItemController::class, 'getDeviceItems'])->name('api.device-items');
        Route::post('/device-items/store', [DeviceItemController::class, 'store'])->name('device-items.store');
        Route::put('/device-items/{id}', [DeviceItemController::class, 'update'])->name('device-items.update');
        Route::delete('/device-items/{id}', [DeviceItemController::class, 'destroy'])->name('device-items.destroy');
        Route::resource('borrows', BorrowController::class);
        Route::get('/borrows/device-items/{device_id}', [BorrowController::class, 'getDeviceItems']);
        Route::post('borrows/{id}/approve', [BorrowController::class, 'approve'])->name('borrows.approve');
        Route::post('borrows/{id}/return', [BorrowController::class, 'markReturned'])->name('borrows.return');
        Route::post('borrows/{id}/cancel', [BorrowController::class, 'cancel'])->name('borrows.cancel');
        Route::get('/borrows/{id}/details', [BorrowController::class, 'getBorrowDetails'])->name('borrows.details');
        Route::resource('maintenances', MaintenanceController::class);
        Route::post('maintenances/{maintenance}/update-status', [MaintenanceController::class, 'updateStatus'])->name('maintenances.update-status');
    });
});
Route::get('/api/device-items/{deviceId}', [DeviceItemController::class, 'getDeviceItems'])->name('api.device-items');
require __DIR__ . '/auth.php';

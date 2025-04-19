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
use App\Http\Controllers\admin\QrCodeController;
use App\Http\Controllers\admin\ReportController;


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
        Route::get('/device-items', [DeviceItemController::class, 'index'])->name('device-items.index');
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
        Route::post('maintenances/check-periodic', [MaintenanceController::class, 'checkPeriodicMaintenance'])->name('maintenances.check-periodic');
        Route::get('device-items/{id}/qrcode', [QrCodeController::class, 'show'])->name('qrcode.show');
        Route::post('device-items/{id}/qrcode/regenerate', [QrCodeController::class, 'regenerate'])->name('qrcode.regenerate');
        Route::get('device-items/{id}/qrcode/history', [QrCodeController::class, 'history'])->name('qrcode.history');
        Route::post('qrcode/print', [QrCodeController::class, 'printMultiple'])->name('qrcode.print');
        
        // Report routes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/device-status', [ReportController::class, 'deviceStatus'])->name('reports.device-status');
        Route::get('/reports/device-status/pdf', [ReportController::class, 'deviceStatusPdf'])->name('reports.device-status-pdf');
        Route::get('/reports/department-assets', [ReportController::class, 'departmentAssets'])->name('reports.department-assets');
        Route::get('/reports/department-assets/pdf', [ReportController::class, 'departmentAssetsPdf'])->name('reports.department-assets-pdf');
        Route::get('/reports/department-assets/excel', [ReportController::class, 'departmentAssetsExcel'])->name('reports.department-assets-excel');
        Route::get('/reports/maintenance-costs', [ReportController::class, 'maintenanceCosts'])->name('reports.maintenance-costs');
        Route::get('/reports/maintenance-costs/pdf', [ReportController::class, 'maintenanceCostsPdf'])->name('reports.maintenance-costs-pdf');
        Route::get('/reports/maintenance-costs/excel', [ReportController::class, 'maintenanceCostsExcel'])->name('reports.maintenance-costs-excel');
    });
});
Route::get('/api/device-items/{deviceId}', [DeviceItemController::class, 'getDeviceItems'])->name('api.device-items');

// Public QR code scan route
Route::get('scan/{token}', [QrCodeController::class, 'scan'])->name('device-items.scan');
Route::post('scan/{token}/update-status', [QrCodeController::class, 'updateStatus'])->name('qrcode.update-status');

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/{id}', [InventoryController::class, 'show'])->name('show');
        Route::post('/{id}/update', [InventoryController::class, 'update'])->name('update');
        Route::post('/{id}/open-box', [InventoryController::class, 'openBox'])->name('open-box');
    });

    // Transfer Management
    // Route::prefix('transfers')->name('transfers.')->group(function () {
    //     Route::get('/', [TransferController::class, 'index'])->name('index');
    //     Route::get('/create', [TransferController::class, 'create'])->name('create');
    //     Route::post('/', [TransferController::class, 'store'])->name('store');
    //     Route::get('/{id}', [TransferController::class, 'show'])->name('show');         // transfers.show
        

    //     Route::post('/{id}/approve', [TransferController::class, 'approve'])->name('approve');
    //     Route::post('/{id}/receive', [TransferController::class, 'receive'])->name('receive');
    //     Route::post('/{id}/cancel', [TransferController::class, 'cancel'])->name('cancel');
    //     Route::post('/transfers/{id}/process-approval', [TransferController::class, 'processApproval'])->name('transfers.process-approval');
    // });
    // Transfer Management
Route::prefix('transfers')->name('transfers.')->group(function () {
    Route::get('/', [TransferController::class, 'index'])->name('index');
    Route::get('/create', [TransferController::class, 'create'])->name('create');
    Route::post('/', [TransferController::class, 'store'])->name('store');
    Route::get('/{id}', [TransferController::class, 'show'])->name('show');

    // These should be standard and clean
    Route::get('/{id}/approve', [TransferController::class, 'approve'])->name('approve'); // GET to show the page
    Route::post('/{id}/process-approval', [TransferController::class, 'processApproval'])->name('process-approval'); // POST to save
    Route::post('/{id}/receive', [TransferController::class, 'receive'])->name('receive');
    Route::post('/{id}/cancel', [TransferController::class, 'cancel'])->name('cancel');
});

    // Sales Management
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
        Route::get('/{id}', [SaleController::class, 'show'])->name('show');
        Route::get('/{id}/receipt', [SaleController::class, 'receipt'])->name('receipt');
    });

    // Complaint Management
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [ComplaintController::class, 'index'])->name('index');
        Route::get('/create', [ComplaintController::class, 'create'])->name('create');
        Route::post('/', [ComplaintController::class, 'store'])->name('store');
        Route::get('/{id}', [ComplaintController::class, 'show'])->name('show');
        Route::post('/{id}/notify-supplier', [ComplaintController::class, 'notifySupplier'])->name('notify-supplier');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/transfers', [ReportController::class, 'transfers'])->name('transfers');
        Route::get('/complaints', [ReportController::class, 'complaints'])->name('complaints');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });
});

require __DIR__.'/auth.php';
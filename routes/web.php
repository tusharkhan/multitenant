<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HouseOwner\HouseOwnerController;
use App\Http\Controllers\HouseOwner\BillController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // House Owners Management
    Route::get('/house-owners', [AdminController::class, 'houseOwners'])->name('house_owners.index');
    Route::get('/house-owners/create', [AdminController::class, 'createHouseOwner'])->name('house_owners.create');
    Route::post('/house-owners', [AdminController::class, 'storeHouseOwner'])->name('house_owners.store');
    Route::get('/house-owners/{houseOwner}/edit', [AdminController::class, 'editHouseOwner'])->name('house_owners.edit');
    Route::put('/house-owners/{houseOwner}', [AdminController::class, 'updateHouseOwner'])->name('house_owners.update');
    
    // Tenants Management
    Route::get('/tenants', [AdminController::class, 'tenants'])->name('tenants.index');
    Route::get('/tenants/create', [AdminController::class, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [AdminController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{tenant}/edit', [AdminController::class, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [AdminController::class, 'updateTenant'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [AdminController::class, 'destroyTenant'])->name('tenants.destroy');
});

// House Owner Routes
Route::middleware(['auth', 'house_owner', 'tenant.scope'])->prefix('house-owner')->name('house_owner.')->group(function () {
    // Buildings Management
    Route::get('/buildings', [HouseOwnerController::class, 'buildings'])->name('buildings.index');
    Route::get('/buildings/create', [HouseOwnerController::class, 'createBuilding'])->name('buildings.create');
    Route::post('/buildings', [HouseOwnerController::class, 'storeBuilding'])->name('buildings.store');
    Route::get('/buildings/{building}/edit', [HouseOwnerController::class, 'editBuilding'])->name('buildings.edit');
    Route::put('/buildings/{building}', [HouseOwnerController::class, 'updateBuilding'])->name('buildings.update');
    
    // Flats Management
    Route::get('/buildings/{building}/flats', [HouseOwnerController::class, 'flats'])->name('flats.index');
    Route::get('/buildings/{building}/flats/create', [HouseOwnerController::class, 'createFlat'])->name('flats.create');
    Route::post('/buildings/{building}/flats', [HouseOwnerController::class, 'storeFlat'])->name('flats.store');
    Route::get('/buildings/{building}/flats/{flat}/edit', [HouseOwnerController::class, 'editFlat'])->name('flats.edit');
    Route::put('/buildings/{building}/flats/{flat}', [HouseOwnerController::class, 'updateFlat'])->name('flats.update');
    Route::delete('/buildings/{building}/flats/{flat}', [HouseOwnerController::class, 'destroyFlat'])->name('flats.destroy');
    
    // Bill Categories Management
    Route::get('/bill-categories', [HouseOwnerController::class, 'billCategories'])->name('bill_categories.index');
    Route::get('/bill-categories/create', [HouseOwnerController::class, 'createBillCategory'])->name('bill_categories.create');
    Route::post('/bill-categories', [HouseOwnerController::class, 'storeBillCategory'])->name('bill_categories.store');
    Route::get('/bill-categories/{billCategory}/edit', [HouseOwnerController::class, 'editBillCategory'])->name('bill_categories.edit');
    Route::put('/bill-categories/{billCategory}', [HouseOwnerController::class, 'updateBillCategory'])->name('bill_categories.update');
    Route::patch('/bill-categories/{billCategory}/toggle-status', [HouseOwnerController::class, 'toggleBillCategoryStatus'])->name('bill_categories.toggle_status');
    Route::delete('/bill-categories/{billCategory}', [HouseOwnerController::class, 'destroyBillCategory'])->name('bill_categories.destroy');
    
    // Bills Management
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
    Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
    Route::get('/bills/{bill}', [BillController::class, 'show'])->name('bills.show');
    Route::get('/bills/{bill}/edit', [BillController::class, 'edit'])->name('bills.edit');
    Route::put('/bills/{bill}', [BillController::class, 'update'])->name('bills.update');
    Route::patch('/bills/{bill}/mark-paid', [BillController::class, 'markAsPaid'])->name('bills.mark_paid');
    
    // Bulk Bill Creation
    Route::get('/bills/bulk/create', [BillController::class, 'bulkCreate'])->name('bills.bulk_create');
    Route::post('/bills/bulk', [BillController::class, 'bulkStore'])->name('bills.bulk_store');
    
    // AJAX Routes
    Route::get('/buildings/{building}/flats-json', [BillController::class, 'getFlats'])->name('buildings.flats');
    Route::get('/buildings/{building}/categories-json', [BillController::class, 'getBillCategories'])->name('buildings.categories');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\EdgeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WashController;
use App\Http\Controllers\BlendController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ShapeController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FeatherController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RmStockController;
use App\Http\Controllers\WBHouseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RmResultController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailSkpController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\DcertificateController;
use App\Http\Controllers\ProductIdentifierController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'can:view-dashboard'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'can:Super Admin'])->group(function () {
    // Role
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/delete-multiple', [UserController::class, 'deleteMultiple']);

    // Company
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::post('/company', [CompanyController::class, 'storeOrUpdate'])->name('company.storeOrUpdate');
    
    // Department
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Employee
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::post('/employees/delete-multiple', [EmployeeController::class, 'deleteMultiple']);
    
    // Area
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::get('/areas/{id}', [AreaController::class, 'show'])->name('areas.show');
    Route::put('/areas/{id}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{id}', [AreaController::class, 'destroy'])->name('areas.destroy');

    // Rumah Burung
    Route::get('/wbhouses', [WbhouseController::class, 'index'])->name('wbhouses.index');
    Route::post('/wbhouses', [WbhouseController::class, 'store'])->name('wbhouses.store');
    Route::get('/wbhouses/{id}', [WbhouseController::class, 'show'])->name('wbhouses.show');
    Route::put('/wbhouses/{id}', [WbhouseController::class, 'update'])->name('wbhouses.update');
    Route::delete('/wbhouses/{id}', [WbhouseController::class, 'destroy'])->name('wbhouses.destroy');
    Route::post('/wbhouses/delete-multiple', [WbhouseController::class, 'deleteMultiple']);
    Route::get('/wbhouses/{id}', function($id){
        return App\Models\WBHouse::with('area')->findOrFail($id);
    });

    // Jenis Bulu
    Route::get('/feathers', [FeatherController::class, 'index'])->name('feathers.index');
    Route::post('/feathers', [FeatherController::class, 'store'])->name('feathers.store');
    Route::get('/feathers/{id}', [FeatherController::class, 'show'])->name('feathers.show');
    Route::put('/feathers/{id}', [FeatherController::class, 'update'])->name('feathers.update');
    Route::delete('/feathers/{id}', [FeatherController::class, 'destroy'])->name('feathers.destroy');

    // Jenis Warna
    Route::get('/colors', [ColorController::class, 'index'])->name('colors.index');
    Route::post('/colors', [ColorController::class, 'store'])->name('colors.store');
    Route::get('/colors/{id}', [ColorController::class, 'show'])->name('colors.show');
    Route::put('/colors/{id}', [ColorController::class, 'update'])->name('colors.update');
    Route::delete('/colors/{id}', [ColorController::class, 'destroy'])->name('colors.destroy');

    // Jenis Bentuk
    Route::get('/shapes', [ShapeController::class, 'index'])->name('shapes.index');
    Route::post('/shapes', [ShapeController::class, 'store'])->name('shapes.store');
    Route::get('/shapes/{id}', [ShapeController::class, 'show'])->name('shapes.show');
    Route::put('/shapes/{id}', [ShapeController::class, 'update'])->name('shapes.update');
    Route::delete('/shapes/{id}', [ShapeController::class, 'destroy'])->name('shapes.destroy');

    // Blend UI
    Route::get('/types', [BlendController::class, 'type'])->name('types.type');
    Route::get('/areas-wbhouses', [BlendController::class, 'area_wbhouse'])->name('areas-wbhouses.area_wbhouse');

    // Jenis Grade
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{id}', [GradeController::class, 'show'])->name('grades.show');
    Route::put('/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{id}', [GradeController::class, 'destroy'])->name('grades.destroy');

    // Mobil
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');
    Route::put('/cars/{id}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');

    // Area
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::get('/areas/{id}', [AreaController::class, 'show'])->name('areas.show');
    Route::put('/areas/{id}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{id}', [AreaController::class, 'destroy'])->name('areas.destroy');

    // Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Customer
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // DCertificate
    Route::get('/dcertificates', [DcertificateController::class, 'index'])->name('dcertificates.index');
    Route::post('/dcertificates', [DcertificateController::class, 'store'])->name('dcertificates.store');
    Route::get('/dcertificates/{id}', [DcertificateController::class, 'show'])->name('dcertificates.show');
    Route::put('/dcertificates/{id}', [DcertificateController::class, 'update'])->name('dcertificates.update');
    Route::delete('/dcertificates/{id}', [DcertificateController::class, 'destroy'])->name('dcertificates.destroy');
    Route::get('/dcertificates/{id}/export', [DcertificateController::class, 'export'])->name('dcertificates.export');
    Route::post('/dcertificates/delete-multiple', [DcertificateController::class, 'deleteMultiple']);

    // Detail SKP
    Route::get('/details', [DetailSkpController::class, 'index'])->name('details.index');
    Route::post('/details', [DetailSkpController::class, 'store'])->name('details.store');
    Route::get('/details/{id}', [DetailSkpController::class, 'show'])->name('details.show');
    Route::put('/details/{id}', [DetailSkpController::class, 'update'])->name('details.update');
    Route::delete('/details/{id}', [DetailSkpController::class, 'destroy'])->name('details.destroy');
    Route::post('/details/delete-multiple', [DetailSkpController::class, 'deleteMultiple']);

    // Arrival
    Route::get('/arrivals', [ArrivalController::class, 'index'])->name('arrivals.index');
    Route::post('/arrivals', [ArrivalController::class, 'store'])->name('arrivals.store');
    Route::get('/arrivals/{id}', [ArrivalController::class, 'show'])->name('arrivals.show');
    Route::put('/arrivals/{id}', [ArrivalController::class, 'update'])->name('arrivals.update');
    Route::delete('/arrivals/{id}', [ArrivalController::class, 'destroy'])->name('arrivals.destroy');
    Route::post('/arrivals/delete-multiple', [ArrivalController::class, 'deleteMultiple']);

    // Container
    Route::get('/containers', [ContainerController::class, 'index'])->name('containers.index');
    Route::post('/containers', [ContainerController::class, 'store'])->name('containers.store');
    Route::get('/containers/{id}', [ContainerController::class, 'show'])->name('containers.show');
    Route::put('/containers/{id}', [ContainerController::class, 'update'])->name('containers.update');
    Route::delete('/containers/{id}', [ContainerController::class, 'destroy'])->name('containers.destroy');
    Route::post('/containers/delete-multiple', [ContainerController::class, 'deleteMultiple']);
    Route::post('/containers/bulk', [ContainerController::class, 'bulk'])->name('containers.bulk');

    // Raw Material
    Route::get('/rawMaterials', [RawMaterialController::class, 'index'])->name('rawMaterials.index');
    Route::get('/raw-material-info/{id}', [RawMaterialController::class, 'info']);

    // Stok
    Route::get('/rmstocks', [RmStockController::class, 'index'])->name('rmstocks.index');
    Route::post('/rmstocks', [RmStockController::class, 'store'])->name('rmstocks.store');
    Route::get('/rmstocks/{id}', [RmStockController::class, 'show'])->name('rmstocks.show');
    Route::put('/rmstocks/{id}', [RmStockController::class, 'update'])->name('rmstocks.update');
    Route::delete('/rmstocks/{id}', [RmStockController::class, 'destroy'])->name('rmstocks.destroy');
    Route::post('/rmstocks/delete-multiple', [RmStockController::class, 'deleteMultiple']);
    Route::get('/raw-material-info/{id}', [RmStockController::class, 'materialInfo']);
    // $stocks = RmStock::with('rawMaterial', 'employee')->oldest()->get();

    // Pengidentifikasi Produk
    Route::get('/identifiers', [ProductIdentifierController::class, 'index'])->name('identifiers.index');
    Route::post('/identifiers', [ProductIdentifierController::class, 'store'])->name('identifiers.store');
    Route::get('/identifiers/{id}', [ProductIdentifierController::class, 'show'])->name('identifiers.show');
    Route::put('/identifiers/{id}', [ProductIdentifierController::class, 'update'])->name('identifiers.update');
    Route::delete('/identifiers/{id}', [ProductIdentifierController::class, 'destroy'])->name('identifiers.destroy');
    Route::post('/identifiers/delete-multiple', [ProductIdentifierController::class, 'deleteMultiple']);

    // Jenis Uji
    Route::get('/testTypes', [TestTypeController::class, 'index'])->name('testTypes.index');
    Route::post('/testTypes', [TestTypeController::class, 'store'])->name('testTypes.store');
    Route::get('/testTypes/{id}', [TestTypeController::class, 'show'])->name('testTypes.show');
    Route::put('/testTypes/{id}', [TestTypeController::class, 'update'])->name('testTypes.update');
    Route::delete('/testTypes/{id}', [TestTypeController::class, 'destroy'])->name('testTypes.destroy');

    // Hasil Uji
    Route::get('/rm-results', [RmResultController::class, 'index'])->name('rm-results.index');
    Route::post('/rm-results', [RmResultController::class, 'store'])->name('rm-results.store');
    Route::get('/rm-results/{id}', [RmResultController::class, 'show'])->name('rm-results.show');
    Route::put('/rm-results/{id}', [RmResultController::class, 'update'])->name('rm-results.update');
    Route::delete('/rm-results/{id}', [RmResultController::class, 'destroy'])->name('rm-results.destroy');
    Route::post('/rm-results/delete-multiple', [RmResultController::class, 'deleteMultiple']);
    Route::post('/rm-results/bulk', [RmResultController::class, 'bulk'])->name('rm-results.bulk');

    // History
    Route::get('/histories', [HistoryController::class, 'index'])->name('histories.index');
    Route::get('/histories-info/{id}', [HistoryController::class, 'info']);
    
    // Sesek kaki
    Route::get('/edges', [EdgeController::class, 'index'])->name('edges.index');
    Route::post('/edges', [EdgeController::class, 'store'])->name('edges.store');
    Route::get('/edges/{id}', [EdgeController::class, 'show'])->name('edges.show');
    Route::put('/edges/{id}', [EdgeController::class, 'update'])->name('edges.update');
    Route::delete('/edges/{id}', [EdgeController::class, 'destroy'])->name('edges.destroy');
    Route::post('/edges/delete-multiple', [EdgeController::class, 'deleteMultiple']);
    
    // Pencucian
    Route::get('/washes', [WashController::class, 'index'])->name('washes.index');
    Route::post('/washes', [WashController::class, 'store'])->name('washes.store');
    Route::get('/washes/{id}', [WashController::class, 'show'])->name('washes.show');
    Route::put('/washes/{id}', [WashController::class, 'update'])->name('washes.update');
    Route::delete('/washes/{id}', [WashController::class, 'destroy'])->name('washes.destroy');
    Route::post('/washes/delete-multiple', [WashController::class, 'deleteMultiple']);
});

require __DIR__.'/auth.php';

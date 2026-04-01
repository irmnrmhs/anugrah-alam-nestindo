<?php

use App\Http\Controllers\AnalysisCertficateController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\BlendController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Ccp1Controller;
use App\Http\Controllers\CcpAlumController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CorrectionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DcertificateController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DetailDocumentController;
use App\Http\Controllers\DetailSkpController;
use App\Http\Controllers\DetailSteamController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DryController;
use App\Http\Controllers\EdgeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FeatherController;
use App\Http\Controllers\FinishedController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightDetailController;
use App\Http\Controllers\FpAlumController;
use App\Http\Controllers\FpGradeController;
use App\Http\Controllers\FpResultController;
use App\Http\Controllers\FpStockController;
use App\Http\Controllers\GradeColorController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeFeatherController;
use App\Http\Controllers\GradeShapeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\NestTypeController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageInspectionController;
use App\Http\Controllers\PackageTypeController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\PickController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductIdentifierController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PullController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RinseController;
use App\Http\Controllers\RmAlumController;
use App\Http\Controllers\RmResultController;
use App\Http\Controllers\RmStockController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShapeController;
use App\Http\Controllers\SoakController;
use App\Http\Controllers\SteamController;
use App\Http\Controllers\SteamOfficerController;
use App\Http\Controllers\SteamUploadController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WashController;
use App\Http\Controllers\WaterController;
use App\Http\Controllers\WBHouseController;
use Illuminate\Support\Facades\Route;

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
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/delete-multiple', [UserController::class, 'deleteMultiple']);
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    // Company
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::post('/company', [CompanyController::class, 'storeOrUpdate'])->name('company.storeOrUpdate');
    
    // Department
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    
    // Dokumen
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Detail Dokumen
    Route::get('det-documents', [DetailDocumentController::class, 'index'])->name('det-documents.index');
    Route::post('/det-documents', [DetailDocumentController::class, 'store'])->name('det-documents.store');
    Route::get('/det-documents/{id}', [DetailDocumentController::class, 'show'])->name('det-documents.show');
    Route::put('/det-documents/{id}', [DetailDocumentController::class, 'update'])->name('det-documents.update');
    Route::delete('/det-documents/{id}', [DetailDocumentController::class, 'destroy'])->name('det-documents.destroy');
    
    // Jabatan
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
    Route::get('/positions/{id}', [PositionController::class, 'show'])->name('positions.show');
    Route::put('/positions/{id}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{id}', [PositionController::class, 'destroy'])->name('positions.destroy');

    // Employee
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::post('/employees/delete-multiple', [EmployeeController::class, 'deleteMultiple']);
    Route::get('/employees/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    
    // Area
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::get('/areas/{id}', [AreaController::class, 'show'])->name('areas.show');
    Route::put('/areas/{id}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{id}', [AreaController::class, 'destroy'])->name('areas.destroy');

    // Rumah Burung
    Route::get('/wbhouses', [WBHouseController::class, 'index'])->name('wbhouses.index');
    Route::post('/wbhouses', [WBHouseController::class, 'store'])->name('wbhouses.store');
    Route::put('/wbhouses/{id}', [WBHouseController::class, 'update'])->name('wbhouses.update');
    Route::delete('/wbhouses/{id}', [WBHouseController::class, 'destroy'])->name('wbhouses.destroy');
    Route::post('/wbhouses/delete-multiple', [WBHouseController::class, 'deleteMultiple']);
    Route::get('/wbhouses/template', [WBHouseController::class, 'downloadTemplate'])->name('wbhouses.template');
    Route::get('/wbhouses/{id}', [WBHouseController::class, 'show'])->name('wbhouses.show');
    Route::get('/wbhouses/{id}', function($id){
        return App\Models\WBHouse::with('area')->findOrFail($id);
    });
    Route::get('/wbhouses/by-kh/{kh}', [WBHouseController::class, 'byKh']);

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
    // Route::get('/areas-wbhouses', [BlendController::class, 'area_wbhouse'])->name('areas-wbhouses.area_wbhouse');

    // Jenis Grade
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{id}', [GradeController::class, 'show'])->name('grades.show');
    Route::put('/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{id}', [GradeController::class, 'destroy'])->name('grades.destroy');

    // Proses
    Route::get('/steps', [StepController::class, 'index'])->name('steps.index');
    Route::post('/steps', [StepController::class, 'store'])->name('steps.store');
    Route::get('/steps/{id}', [StepController::class, 'show'])->name('steps.show');
    Route::put('/steps/{id}', [StepController::class, 'update'])->name('steps.update');
    Route::delete('/steps/{id}', [StepController::class, 'destroy'])->name('steps.destroy');
    
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
    Route::get('/dcertificates/{id}/preview', [DcertificateController::class, 'preview'])->name('dcertificates.preview');
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
    Route::get('/arrivals/{id}/export', [ArrivalController::class, 'export'])->name('arrivals.export');
    Route::post('/arrivals/delete-multiple', [ArrivalController::class, 'deleteMultiple']);
    Route::get('/arrivals/{id}/preview', [ArrivalController::class, 'preview'])->name('arrivals.preview');

    // Container
    Route::get('/containers', [ContainerController::class, 'index'])->name('containers.index');
    Route::post('/containers', [ContainerController::class, 'store'])->name('containers.store');
    Route::get('/containers/{id}', [ContainerController::class, 'show'])->name('containers.show');
    Route::put('/containers/{id}', [ContainerController::class, 'update'])->name('containers.update');
    Route::delete('/containers/{id}', [ContainerController::class, 'destroy'])->name('containers.destroy');
    Route::post('/containers/delete-multiple', [ContainerController::class, 'deleteMultiple']);
    Route::post('/containers/bulk', [ContainerController::class, 'bulk'])->name('containers.bulk');
    Route::get('/containers/{arrival}/export', [ContainerController::class, 'export'])->name('containers.export');


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
    Route::post('/rmstocks/bulk', [RmStockController::class, 'bulk'])->name('rmstocks.bulk');
    Route::get('/rmstocks/export/{id}', [RmStockController::class, 'export'])->name('rmstocks.export');

    // Pengidentifikasi Produk
    Route::get('/identifiers', [ProductIdentifierController::class, 'index'])->name('identifiers.index');
    Route::post('/identifiers', [ProductIdentifierController::class, 'store'])->name('identifiers.store');
    Route::get('/identifiers/{id}', [ProductIdentifierController::class, 'show'])->name('identifiers.show');
    Route::put('/identifiers/{id}', [ProductIdentifierController::class, 'update'])->name('identifiers.update');
    Route::delete('/identifiers/{id}', [ProductIdentifierController::class, 'destroy'])->name('identifiers.destroy');
    Route::post('/identifiers/delete-multiple', [ProductIdentifierController::class, 'deleteMultiple']);
    Route::get('/raw-material-info-pi/{id}', [ProductIdentifierController::class, 'materialInfo']);
    Route::get('/identifiers/{id}/preview', [ProductIdentifierController::class, 'preview'])->name('identifiers.preview');
    Route::get('/identifiers/{id}/export', [ProductIdentifierController::class, 'export'])->name('identifiers.export');

    // Grading Bentuk
    Route::get('/gshapes', [GradeShapeController::class, 'index'])->name('gshapes.index');
    Route::post('/gshapes', [GradeShapeController::class, 'store'])->name('gshapes.store');
    Route::get('/gshapes/{id}', [GradeShapeController::class, 'show'])->name('gshapes.show');
    Route::put('/gshapes/{id}', [GradeShapeController::class, 'update'])->name('gshapes.update');
    Route::delete('/gshapes/{id}', [GradeShapeController::class, 'destroy'])->name('gshapes.destroy');
    Route::post('/gshapes/delete-multiple', [GradeShapeController::class, 'deleteMultiple']);
    Route::get('/raw-material-info-gs/{id}', [GradeShapeController::class, 'materialInfo']);

    // Grading Bulu
    Route::get('/gfeathers', [GradeFeatherController::class, 'index'])->name('gfeathers.index');
    Route::post('/gfeathers', [GradeFeatherController::class, 'store'])->name('gfeathers.store');
    Route::get('/gfeathers/{id}', [GradeFeatherController::class, 'show'])->name('gfeathers.show');
    Route::put('/gfeathers/{id}', [GradeFeatherController::class, 'update'])->name('gfeathers.update');
    Route::delete('/gfeathers/{id}', [GradeFeatherController::class, 'destroy'])->name('gfeathers.destroy');
    Route::post('/gfeathers/delete-multiple', [GradeFeatherController::class, 'deleteMultiple']);
    Route::get('/raw-material-info-gf/{id}', [GradeFeatherController::class, 'materialInfo']);
    Route::get('/gfeathers/{id}/export', [GradeFeatherController::class, 'export'])->name('gfeathers.export');

    // Grading Warna
    Route::get('/gcolors', [GradeColorController::class, 'index'])->name('gcolors.index');
    Route::post('/gcolors', [GradeColorController::class, 'store'])->name('gcolors.store');
    Route::get('/gcolors/{id}', [GradeColorController::class, 'show'])->name('gcolors.show');
    Route::put('/gcolors/{id}', [GradeColorController::class, 'update'])->name('gcolors.update');
    Route::delete('/gcolors/{id}', [GradeColorController::class, 'destroy'])->name('gcolors.destroy');
    Route::post('/gcolors/delete-multiple', [GradeColorController::class, 'deleteMultiple']);
    Route::get('/raw-material-info-gc/{id}', [GradeColorController::class, 'materialInfo']);
    Route::get('/gcolors/{id}/export', [GradeColorController::class, 'export'])->name('gcolors.export');
    Route::get('/gcolor-feathers/{rms_id}', [GradeColorController::class, 'getFeathersByRms']);

    // Jenis Uji
    Route::get('/testTypes', [TestTypeController::class, 'index'])->name('testTypes.index');
    Route::post('/testTypes', [TestTypeController::class, 'store'])->name('testTypes.store');
    Route::get('/testTypes/{id}', [TestTypeController::class, 'show'])->name('testTypes.show');
    Route::put('/testTypes/{id}', [TestTypeController::class, 'update'])->name('testTypes.update');
    Route::delete('/testTypes/{id}', [TestTypeController::class, 'destroy'])->name('testTypes.destroy');

    // Air Produksi
    Route::get('/waters', [WaterController::class, 'index'])->name('waters.index');
    Route::post('/waters', [WaterController::class, 'store'])->name('waters.store');
    Route::get('/waters/{id}', [WaterController::class, 'show'])->name('waters.show');
    Route::put('/waters/{id}', [WaterController::class, 'update'])->name('waters.update');
    Route::delete('/waters/{id}', [WaterController::class, 'destroy'])->name('waters.destroy');

    // Hasil Uji BB
    Route::get('/rm-results', [RmResultController::class, 'index'])->name('rm-results.index');
    Route::post('/rm-results', [RmResultController::class, 'store'])->name('rm-results.store');
    Route::get('/rm-results/{id}', [RmResultController::class, 'show'])->name('rm-results.show');
    Route::put('/rm-results/{id}', [RmResultController::class, 'update'])->name('rm-results.update');
    Route::delete('/rm-results/{id}', [RmResultController::class, 'destroy'])->name('rm-results.destroy');
    Route::post('/rm-results/delete-multiple', [RmResultController::class, 'deleteMultiple']);
    Route::post('/rm-results/bulk', [RmResultController::class, 'bulk'])->name('rm-results.bulk');
    Route::get('/rm-results/{id}/water', [RmResultController::class, 'water'])->name('rm-results.water');
    Route::get('/rm-results/{id}/nitrite', [RmResultController::class, 'nitrite'])->name('rm-results.nitrite');

    // Hasil Uji Al BB
    Route::get('/rm-alums', [RmAlumController::class, 'index'])->name('rm-alums.index');
    Route::post('/rm-alums', [RmAlumController::class, 'store'])->name('rm-alums.store');
    Route::get('/rm-alums/{id}', [RmAlumController::class, 'show'])->name('rm-alums.show');
    Route::put('/rm-alums/{id}', [RmAlumController::class, 'update'])->name('rm-alums.update');
    Route::delete('/rm-alums/{id}', [RmAlumController::class, 'destroy'])->name('rm-alums.destroy');
    Route::post('/rm-alums/delete-multiple', [RmAlumController::class, 'deleteMultiple']);
    Route::post('/rm-alums/bulk', [RmAlumController::class, 'bulk'])->name('rm-alums.bulk');
    Route::get('/rm-alums/{id}/export', [RmAlumController::class, 'export'])->name('rm-alums.export');

    // Hasil Uji CCP1 Nitrit
    Route::get('/ccp1', [Ccp1Controller::class, 'index'])->name('ccp1.index');
    Route::post('/ccp1', [Ccp1Controller::class, 'store'])->name('ccp1.store');
    Route::get('/ccp1/{id}', [Ccp1Controller::class, 'show'])->name('ccp1.show');
    Route::put('/ccp1/{id}', [Ccp1Controller::class, 'update'])->name('ccp1.update');
    Route::delete('/ccp1/{id}', [Ccp1Controller::class, 'destroy'])->name('ccp1.destroy');
    Route::post('/ccp1/delete-multiple', [Ccp1Controller::class, 'deleteMultiple']);
    Route::post('/ccp1/bulk', [Ccp1Controller::class, 'bulk'])->name('ccp1.bulk');
    Route::get('/ccp1/{id}/export', [Ccp1Controller::class, 'export'])->name('ccp1.export');

    // Hasil Uji CCP1 Aluminium
    Route::get('/ccp-al', [CcpAlumController::class, 'index'])->name('ccp-al.index');
    Route::post('/ccp-al', [CcpAlumController::class, 'store'])->name('ccp-al.store');
    Route::get('/ccp-al/{id}', [CcpAlumController::class, 'show'])->name('ccp-al.show');
    Route::put('/ccp-al/{id}', [CcpAlumController::class, 'update'])->name('ccp-al.update');
    Route::delete('/ccp-al/{id}', [CcpAlumController::class, 'destroy'])->name('ccp-al.destroy');
    Route::post('/ccp-al/delete-multiple', [CcpAlumController::class, 'deleteMultiple']);
    Route::post('/ccp-al/bulk', [CcpAlumController::class, 'bulk'])->name('ccp-al.bulk');
    Route::get('/ccp-al/{id}/export', [CcpAlumController::class, 'export'])->name('ccp-al.export');

    // Hasil Uji Air dan Nitrit PJ
    Route::get('/fp-results', [FpResultController::class, 'index'])->name('fp-results.index');
    Route::post('/fp-results', [FpResultController::class, 'store'])->name('fp-results.store');
    Route::get('/fp-results/{id}', [FpResultController::class, 'show'])->name('fp-results.show');
    Route::put('/fp-results/{id}', [FpResultController::class, 'update'])->name('fp-results.update');
    Route::delete('/fp-results/{id}', [FpResultController::class, 'destroy'])->name('fp-results.destroy');
    Route::post('/fp-results/delete-multiple', [FpResultController::class, 'deleteMultiple']);
    Route::post('/fp-results/bulk', [FpResultController::class, 'bulk'])->name('fp-results.bulk');
    Route::get('/fp-results/{id}/water', [FpResultController::class, 'water'])->name('fp-results.water');
    Route::get('/fp-results/{id}/nitrite', [FpResultController::class, 'nitrite'])->name('fp-results.nitrite');

    // Hasil Uji Aluminium PJ
    Route::get('/fp-alums', [FpAlumController::class, 'index'])->name('fp-alums.index');
    Route::post('/fp-alums', [FpAlumController::class, 'store'])->name('fp-alums.store');
    Route::get('/fp-alums/{id}', [FpAlumController::class, 'show'])->name('fp-alums.show');
    Route::put('/fp-alums/{id}', [FpAlumController::class, 'update'])->name('fp-alums.update');
    Route::delete('/fp-alums/{id}', [FpAlumController::class, 'destroy'])->name('fp-alums.destroy');
    Route::post('/fp-alums/delete-multiple', [FpAlumController::class, 'deleteMultiple']);
    Route::post('/fp-alums/bulk', [FpAlumController::class, 'bulk'])->name('fp-alums.bulk');
    Route::get('/fp-alums/{id}/export', [FpAlumController::class, 'export'])->name('fp-alums.export');

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
    Route::get('/edges-info/{id}', [EdgeController::class, 'info']);
    // Route::get('/edges/{id}/export', [EdgeController::class, 'export'])->name('edges.export');
    Route::get('/edges/export/{rawMaterialId}', [EdgeController::class, 'export'])->name('edges.export');
    Route::get('/edges-grades/{rawMaterialId}', [EdgeController::class, 'getGrades']);
    Route::get('/edges-hancuran-info/{id}', [EdgeController::class, 'hancuranInfo']);
    
    // Pencucian
    Route::get('/washes', [WashController::class, 'index'])->name('washes.index');
    Route::post('/washes', [WashController::class, 'store'])->name('washes.store');
    Route::get('/washes/{id}', [WashController::class, 'show'])->name('washes.show');
    Route::put('/washes/{id}', [WashController::class, 'update'])->name('washes.update');
    Route::delete('/washes/{id}', [WashController::class, 'destroy'])->name('washes.destroy');
    Route::post('/washes/delete-multiple', [WashController::class, 'deleteMultiple']);
    Route::get('/washes-info/{id}', [WashController::class, 'info']);
    Route::get('/washes/export/{rawMaterialId}', [WashController::class, 'export'])->name('washes.export');
    Route::get('/washes-grades/{rawMaterialId}', [WashController::class, 'getGrades']);

    // Inspeksi dan Koreksi
    Route::get('/corrections', [CorrectionController::class, 'index'])->name('corrections.index');
    Route::post('/corrections', [CorrectionController::class, 'store'])->name('corrections.store');
    Route::get('/corrections/{id}', [CorrectionController::class, 'show'])->name('corrections.show');
    Route::put('/corrections/{id}', [CorrectionController::class, 'update'])->name('corrections.update');
    Route::delete('/corrections/{id}', [CorrectionController::class, 'destroy'])->name('corrections.destroy');
    Route::post('/corrections/delete-multiple', [CorrectionController::class, 'deleteMultiple']);
    Route::get('/corrections-info/{id}', [CorrectionController::class, 'info']);
    Route::get('/corrections/{id}/export', [CorrectionController::class, 'export'])->name('corrections.export');
    Route::get('/corrections-grades/{rawMaterialId}', [CorrectionController::class, 'getGrades']);
    
    // Pencabutan Bulu
    Route::get('/picks', [PickController::class, 'index'])->name('picks.index');
    Route::post('/picks', [PickController::class, 'store'])->name('picks.store');
    Route::get('/picks/{id}', [PickController::class, 'show'])->name('picks.show');
    Route::put('/picks/{id}', [PickController::class, 'update'])->name('picks.update');
    Route::delete('/picks/{id}', [PickController::class, 'destroy'])->name('picks.destroy');
    Route::post('/picks/delete-multiple', [PickController::class, 'deleteMultiple']);
    Route::get('/picks-info/{id}', [PickController::class, 'info']);
    Route::get('/picks/{id}/export', [PickController::class, 'export'])->name('picks.export');
    Route::get('/picks-grades/{rawMaterialId}', [PickController::class, 'getGrades']);
    
    // Perendaman
    Route::get('/soaks', [SoakController::class, 'index'])->name('soaks.index');
    Route::post('/soaks', [SoakController::class, 'store'])->name('soaks.store');
    Route::get('/soaks/{id}', [SoakController::class, 'show'])->name('soaks.show');
    Route::put('/soaks/{id}', [SoakController::class, 'update'])->name('soaks.update');
    Route::delete('/soaks/{id}', [SoakController::class, 'destroy'])->name('soaks.destroy');
    Route::post('/soaks/delete-multiple', [SoakController::class, 'deleteMultiple']);
    Route::get('/soaks-info/{id}', [SoakController::class, 'info']);
    Route::get('/soaks/{id}/export', [SoakController::class, 'export'])->name('soaks.export');
    Route::get('/soaks-grades/{rawMaterialId}', [SoakController::class, 'getGrades']);
    
    // Cabut Bilas
    Route::get('/rinses', [RinseController::class, 'index'])->name('rinses.index');
    Route::post('/rinses', [RinseController::class, 'store'])->name('rinses.store');
    Route::get('/rinses/{id}', [RinseController::class, 'show'])->name('rinses.show');
    Route::put('/rinses/{id}', [RinseController::class, 'update'])->name('rinses.update');
    Route::delete('/rinses/{id}', [RinseController::class, 'destroy'])->name('rinses.destroy');
    Route::post('/rinses/delete-multiple', [RinseController::class, 'deleteMultiple']);
    Route::get('/rinses-info/{id}', [RinseController::class, 'info']);
    Route::get('/rinses/{id}/export', [RinseController::class, 'export'])->name('rinses.export');
    Route::get('/rinses-grades/{rawMaterialId}', [RinseController::class, 'getGrades']);

    // Masuk Cetak
    Route::get('/entries', [EntryController::class, 'index'])->name('entries.index');
    Route::post('/entries', [EntryController::class, 'store'])->name('entries.store');
    Route::get('/entries/{id}', [EntryController::class, 'show'])->name('entries.show');
    Route::put('/entries/{id}', [EntryController::class, 'update'])->name('entries.update');
    Route::delete('/entries/{id}', [EntryController::class, 'destroy'])->name('entries.destroy');
    Route::post('/entries/delete-multiple', [EntryController::class, 'deleteMultiple']);
    Route::get('/entries-info/{id}', [EntryController::class, 'info']);
    Route::get('/entries/{id}/export', [EntryController::class, 'export'])->name('entries.export');
    Route::get('/entries-grades/{rawMaterialId}', [EntryController::class, 'getGrades']);

    // Keluar Cetak
    Route::get('/pulls', [PullController::class, 'index'])->name('pulls.index');
    Route::post('/pulls', [PullController::class, 'store'])->name('pulls.store');
    Route::get('/pulls/{id}', [PullController::class, 'show'])->name('pulls.show');
    Route::put('/pulls/{id}', [PullController::class, 'update'])->name('pulls.update');
    Route::delete('/pulls/{id}', [PullController::class, 'destroy'])->name('pulls.destroy');
    Route::post('/pulls/delete-multiple', [PullController::class, 'deleteMultiple']);
    Route::get('/pulls-info/{id}', [PullController::class, 'info']);
    Route::get('/pulls/{id}/export', [PullController::class, 'export'])->name('pulls.export');
    Route::get('/pulls-grades/{rawMaterialId}', [PullController::class, 'getGrades']);

    // Pengeringan
    Route::get('/dries', [DryController::class, 'index'])->name('dries.index');
    Route::post('/dries', [DryController::class, 'store'])->name('dries.store');
    Route::get('/dries/{id}', [DryController::class, 'show'])->name('dries.show');
    Route::put('/dries/{id}', [DryController::class, 'update'])->name('dries.update');
    Route::delete('/dries/{id}', [DryController::class, 'destroy'])->name('dries.destroy');
    Route::post('/dries/delete-multiple', [DryController::class, 'deleteMultiple']);
    Route::get('/dries-info/{id}', [DryController::class, 'info']);
    Route::get('/dries/{id}/export', [DryController::class, 'export'])->name('dries.export');
    Route::get('/dries-grades/{rawMaterialId}', [DryController::class, 'getGrades']);

    // Grade Bahan Baku
    Route::get('/fp-grades', [FpGradeController::class, 'index'])->name('fp-grades.index');
    Route::post('/fp-grades', [FpGradeController::class, 'store'])->name('fp-grades.store');
    Route::get('/fp-grades/{id}', [FpGradeController::class, 'show'])->name('fp-grades.show');
    Route::put('/fp-grades/{id}', [FpGradeController::class, 'update'])->name('fp-grades.update');
    Route::delete('/fp-grades/{id}', [FpGradeController::class, 'destroy'])->name('fp-grades.destroy');
    Route::post('/fp-grades/delete-multiple', [FpGradeController::class, 'deleteMultiple']);
    Route::get('/fp-grades/{id}/export', [FpGradeController::class, 'export'])->name('fp-grades.export');

    // Produk
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/delete-multiple', [ProductController::class, 'deleteMultiple']);
    Route::get('/products-info/{id}', [ProductController::class, 'info']);
    Route::get('/products/{id}/export', [ProductController::class, 'export'])->name('products.export');

    // Produk Jadi
    Route::get('/fp-products', [FinishedController::class, 'index'])->name('fp-products.index');
    Route::get('/fp-products-info/{id}', [FinishedController::class, 'info']);

    
    // Stok Produk Jadi
    Route::get('/fp-stocks', [FpStockController::class, 'index'])->name('fp-stocks.index');
    Route::post('/fp-stocks', [FpStockController::class, 'store'])->name('fp-stocks.store');
    Route::get('/fp-stocks/{id}', [FpStockController::class, 'show'])->name('fp-stocks.show');
    Route::put('/fp-stocks/{id}', [FpStockController::class, 'update'])->name('fp-stocks.update');
    Route::delete('/fp-stocks/{id}', [FpStockController::class, 'destroy'])->name('fp-stocks.destroy');
    Route::post('/fp-stocks/delete-multiple', [FpStockController::class, 'deleteMultiple']);
    Route::get('/product-info/{id}', [FpStockController::class, 'productInfo']);

    // Tipe Sarang
    Route::get('/nests', [NestTypeController::class, 'index'])->name('nests.index');
    Route::post('/nests', [NestTypeController::class, 'store'])->name('nests.store');
    Route::get('/nests/{id}', [NestTypeController::class, 'show'])->name('nests.show');
    Route::put('/nests/{id}', [NestTypeController::class, 'update'])->name('nests.update');
    Route::delete('/nests/{id}', [NestTypeController::class, 'destroy'])->name('nests.destroy');
    Route::post('/nests/delete-multiple', [NestTypeController::class, 'deleteMultiple']);

    // Petugas Pemanas
    Route::get('/officers', [SteamOfficerController::class, 'index'])->name('officers.index');
    Route::post('/officers', [SteamOfficerController::class, 'store'])->name('officers.store');
    Route::get('/officers/{id}', [SteamOfficerController::class, 'show'])->name('officers.show');
    Route::put('/officers/{id}', [SteamOfficerController::class, 'update'])->name('officers.update');
    Route::delete('/officers/{id}', [SteamOfficerController::class, 'destroy'])->name('officers.destroy');
    Route::post('/officers/delete-multiple', [SteamOfficerController::class, 'deleteMultiple']);

    // Steaming
    Route::get('/steams', [SteamController::class, 'index'])->name('steams.index');
    Route::post('/steams', [SteamController::class, 'store'])->name('steams.store');
    Route::get('/steams/{id}', [SteamController::class, 'show'])->name('steams.show');
    Route::put('/steams/{id}', [SteamController::class, 'update'])->name('steams.update');
    Route::delete('/steams/{id}', [SteamController::class, 'destroy'])->name('steams.destroy');
    Route::post('/steams/delete-multiple', [SteamController::class, 'deleteMultiple']);
    Route::get('/steams/{id}/export', [SteamController::class, 'export'])->name('steams.export');

    // Detail Steam
    Route::get('/dsteams', [DetailSteamController::class, 'index'])->name('dsteams.index');
    Route::post('/dsteams', [DetailSteamController::class, 'store'])->name('dsteams.store');
    Route::get('/dsteams/{id}', [DetailSteamController::class, 'show'])->name('dsteams.show');
    Route::put('/dsteams/{id}', [DetailSteamController::class, 'update'])->name('dsteams.update');
    Route::delete('/dsteams/{id}', [DetailSteamController::class, 'destroy'])->name('dsteams.destroy');
    Route::post('/dsteams/delete-multiple', [DetailSteamController::class, 'deleteMultiple']);
    Route::get('/dsteams/{id}/export', [DetailSteamController::class, 'export'])->name('dsteams.export');

    // Scan Steaming
    Route::get('/usteams', [SteamUploadController::class, 'index'])->name('usteams.index');
    Route::post('/usteams', [SteamUploadController::class, 'store'])->name('usteams.store');
    Route::get('/usteams/{id}', [SteamUploadController::class, 'show'])->name('usteams.show');
    Route::put('/usteams/{id}', [SteamUploadController::class, 'update'])->name('usteams.update');
    Route::delete('/usteams/{id}', [SteamUploadController::class, 'destroy'])->name('usteams.destroy');
    Route::post('/usteams/delete-multiple', [SteamUploadController::class, 'deleteMultiple']);

    // Packing
    Route::get('/packs', [PackingController::class, 'index'])->name('packs.index');
    Route::post('/packs', [PackingController::class, 'store'])->name('packs.store');
    Route::get('/packs/{id}', [PackingController::class, 'show'])->name('packs.show');
    Route::put('/packs/{id}', [PackingController::class, 'update'])->name('packs.update');
    Route::delete('/packs/{id}', [PackingController::class, 'destroy'])->name('packs.destroy');
    Route::post('/packs/delete-multiple', [PackingController::class, 'deleteMultiple']);
    Route::get('/packs/{id}/export', [PackingController::class, 'export'])->name('packs.export');
    
    // Jenis Bahan Kemas
    Route::get('/package-types', [PackageTypeController::class, 'index'])->name('package-types.index');
    Route::post('/package-types', [PackageTypeController::class, 'store'])->name('package-types.store');
    Route::get('/package-types/{id}', [PackageTypeController::class, 'show'])->name('package-types.show');
    Route::put('/package-types/{id}', [PackageTypeController::class, 'update'])->name('package-types.update');
    Route::delete('/package-types/{id}', [PackageTypeController::class, 'destroy'])->name('package-types.destroy');
    Route::post('/package-types/delete-multiple', [PackageTypeController::class, 'deleteMultiple']);
    
    // Bahan Kemas
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{id}', [PackageController::class, 'show'])->name('packages.show');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');
    Route::post('/packages/delete-multiple', [PackageController::class, 'deleteMultiple']);
    
    // Bahan Kemas
    Route::get('/inspections', [PackageInspectionController::class, 'index'])->name('inspections.index');
    Route::post('/inspections', [PackageInspectionController::class, 'store'])->name('inspections.store');
    Route::get('/inspections/{id}', [PackageInspectionController::class, 'show'])->name('inspections.show');
    Route::put('/inspections/{id}', [PackageInspectionController::class, 'update'])->name('inspections.update');
    Route::delete('/inspections/{id}', [PackageInspectionController::class, 'destroy'])->name('inspections.destroy');
    Route::post('/inspections/delete-multiple', [PackageInspectionController::class, 'deleteMultiple']);

    // Flight
    Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
    Route::post('/flights', [FlightController::class, 'store'])->name('flights.store');
    Route::get('/flights/{id}', [FlightController::class, 'show'])->name('flights.show');
    Route::put('/flights/{id}', [FlightController::class, 'update'])->name('flights.update');
    Route::delete('/flights/{id}', [FlightController::class, 'destroy'])->name('flights.destroy');
    Route::post('/flights/delete-multiple', [FlightController::class, 'deleteMultiple']);
    
    // Flight
    Route::get('/dflights', [FlightDetailController::class, 'index'])->name('dflights.index');
    Route::post('/dflights', [FlightDetailController::class, 'store'])->name('dflights.store');
    Route::get('/dflights/{id}', [FlightDetailController::class, 'show'])->name('dflights.show');
    Route::put('/dflights/{id}', [FlightDetailController::class, 'update'])->name('dflights.update');
    Route::delete('/dflights/{id}', [FlightDetailController::class, 'destroy'])->name('dflights.destroy');
    Route::post('/dflights/delete-multiple', [FlightDetailController::class, 'deleteMultiple']);
    
    // Ekspor
    Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
    Route::post('/exports', [ExportController::class, 'store'])->name('exports.store');
    Route::get('/exports/{id}', [ExportController::class, 'show'])->name('exports.show');
    Route::put('/exports/{id}', [ExportController::class, 'update'])->name('exports.update');
    Route::delete('/exports/{id}', [ExportController::class, 'destroy'])->name('exports.destroy');
    Route::post('/exports/delete-multiple', [ExportController::class, 'deleteMultiple']);
    
    // Order
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/delete-multiple', [OrderController::class, 'deleteMultiple']);
    
    // Ekspor
    Route::get('/order-details', [OrderDetailController::class, 'index'])->name('order-details.index');
    Route::post('/order-details', [OrderDetailController::class, 'store'])->name('order-details.store');
    Route::get('/order-details/{id}', [OrderDetailController::class, 'show'])->name('order-details.show');
    Route::put('/order-details/{id}', [OrderDetailController::class, 'update'])->name('order-details.update');
    Route::delete('/order-details/{id}', [OrderDetailController::class, 'destroy'])->name('order-details.destroy');
    Route::post('/order-details/delete-multiple', [OrderDetailController::class, 'deleteMultiple']);
    
    // Ceklis Kendaraan
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    Route::post('/vehicles/delete-multiple', [VehicleController::class, 'deleteMultiple']);
    
    // Petugas Karantina
    Route::get('/qofficers', [OfficerController::class, 'index'])->name('qofficers.index');
    Route::post('/qofficers', [OfficerController::class, 'store'])->name('qofficers.store');
    Route::get('/qofficers/{id}', [OfficerController::class, 'show'])->name('qofficers.show');
    Route::put('/qofficers/{id}', [OfficerController::class, 'update'])->name('qofficers.update');
    Route::delete('/qofficers/{id}', [OfficerController::class, 'destroy'])->name('qofficers.destroy');
    Route::post('/qofficers/delete-multiple', [OfficerController::class, 'deleteMultiple']);
    
    // Item
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/delete-multiple', [ItemController::class, 'deleteMultiple']);
    
    // Item
    Route::get('/ditems', [ItemDetailController::class, 'index'])->name('ditems.index');
    Route::post('/ditems', [ItemDetailController::class, 'store'])->name('ditems.store');
    Route::get('/ditems/{id}', [ItemDetailController::class, 'show'])->name('ditems.show');
    Route::put('/ditems/{id}', [ItemDetailController::class, 'update'])->name('ditems.update');
    Route::delete('/ditems/{id}', [ItemDetailController::class, 'destroy'])->name('ditems.destroy');
    Route::post('/ditems/delete-multiple', [ItemDetailController::class, 'deleteMultiple']);

    // Jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{id}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('/schedules/delete-multiple', [ScheduleController::class, 'deleteMultiple']);

    // Analisis
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::post('/analysis', [AnalysisController::class, 'store'])->name('analysis.store');
    Route::get('/analysis/{id}', [AnalysisController::class, 'show'])->name('analysis.show');
    Route::put('/analysis/{id}', [AnalysisController::class, 'update'])->name('analysis.update');
    Route::delete('/analysis/{id}', [AnalysisController::class, 'destroy'])->name('analysis.destroy');
    Route::post('/analysis/delete-multiple', [AnalysisController::class, 'deleteMultiple']);

    // Sertifikat Analisis
    Route::get('/certificates', [AnalysisCertficateController::class, 'index'])->name('certificates.index');
    Route::post('/certificates', [AnalysisCertficateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{id}', [AnalysisCertficateController::class, 'show'])->name('certificates.show');
    Route::put('/certificates/{id}', [AnalysisCertficateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{id}', [AnalysisCertficateController::class, 'destroy'])->name('certificates.destroy');
    Route::post('/certificates/delete-multiple', [AnalysisCertficateController::class, 'deleteMultiple']);
});

Route::get('/scan-camera', function () {
        return view('scan.product-report');
    });
Route::get('/scan/{kode}', [ProductReportController::class, 'scan'])->name('barcode.scan.result');

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DynamicFormController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\TaggingController;
use App\Http\Controllers\Admin\UseCaseController;
use App\Http\Controllers\ProfileController;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('', 'admin.customers.add_customer')->name('admin.customers.add');
    Route::view('/admin/customers-list', 'admin.customers.customer_list')->name('admin.customers.list');
    Route::view('/admin/calender', 'admin.calender.calender')->name('admin.calender');
    // Branches Routes
    Route::get('/admin/branches', [BranchController::class, 'index'])->middleware('permission:branches,view')
        ->name('admin.branches');
    Route::post('/admin/branches', [BranchController::class, 'store'])->middleware('permission:branches,create')
        ->name('admin.branches.store');
    Route::post('/admin/branches/{branch}', [BranchController::class, 'update'])->middleware('permission:branches,edit')
        ->name('branches.update');
    Route::delete('/admin/branches/{branch}', [BranchController::class, 'destroy'])->middleware('permission:branches,delete')
        ->name('branches.destroy');
    Route::post('/admin/users/permissions', [BranchController::class, 'storePermissions'])->name('admin.permissions.store');
    Route::get('/admin/users/{user}/permissions', [BranchController::class, 'getPermissions'])->name('admin.permissions.get');
    // Announcements Routes
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->middleware('permission:announcement,view')
        ->name('admin.announcements');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->middleware('permission:announcement,create')
        ->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->middleware('permission:announcement,create')
        ->name('admin.announcements.store');
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->middleware('permission:announcement,view')
        ->name('announcements.show');
    Route::get('/admin/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->middleware('permission:announcement,edit')
        ->name('announcements.edit');
    Route::put('/admin/announcements/{announcement}', [AnnouncementController::class, 'update'])->middleware('permission:announcement,edit')
        ->name('announcements.update');
    Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('permission:announcement,delete')
        ->name('announcements.destroy');
    // Taggings Routes
    Route::get('/admin/tagging', [TaggingController::class, 'index'])->middleware('permission:tagging,view')
        ->name('admin.tagging.list');
    Route::post('/admin/tagging', [TaggingController::class, 'store'])->middleware('permission:tagging,create')
        ->name('taggings.store');
    Route::put('/admin/tagging-update/{tagging}', [TaggingController::class, 'update'])->middleware('permission:tagging,edit')
        ->name('taggings.update');
    Route::delete('/admin/tagging/{tagging}', [TaggingController::class, 'destroy'])->middleware('permission:tagging,delete')
        ->name('admin.tagging.delete');
    // Form Builder Routes
    Route::get('/admin/add-customer', [FormBuilderController::class, 'create'])->name('forms.create');
    Route::post('/admin/forms/store', [FormBuilderController::class, 'store'])->name('forms.store');

    Route::get('/my-form', [DynamicFormController::class, 'show'])->name('forms.my');
    Route::post('/my-form', [DynamicFormController::class, 'submit'])->name('forms.submit');
    // Brands Routes
    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    });
    // Use Cases Routes
    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/use-cases', [UseCaseController::class, 'index'])->name('use-cases.index');
        Route::post('/use-cases', [UseCaseController::class, 'store'])->name('use-cases.store');
        Route::get('/use-cases/{use_case}/edit', [UseCaseController::class, 'edit'])->name('use-cases.edit');
        Route::put('/use-cases/{use_case}', [UseCaseController::class, 'update'])->name('use-cases.update');
        Route::delete('/use-cases/{use_case}', [UseCaseController::class, 'destroy'])->name('use-cases.destroy');
    });
});
// Website Routes
Route::view('/', 'user.customer_form')->name('customer.form');

require __DIR__ . '/auth.php';

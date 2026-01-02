<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\TaggingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/admin/add-customer', 'admin.customers.add_customer')->name('admin.customers.add');
    Route::view('/admin/customers-list', 'admin.customers.customer_list')->name('admin.customers.list');
    Route::view('/admin/calender', 'admin.calender.calender')->name('admin.calender');
    // Tagging Routes
    Route::get('/admin/tagging', [TaggingController::class, 'index'])->name('admin.tagging.list');
    Route::post('taggings-store', [TaggingController::class, 'store'])->name('taggings.store');
    Route::put('taggings-update/{tagging}', [TaggingController::class, 'update'])->name('taggings.update');
    Route::delete('/admin/tagging/{tagging}/delete', [TaggingController::class, 'destroy'])->name('admin.tagging.delete');
    // Branches Routes
    Route::post('/admin/branches', [BranchController::class, 'store'])->name('admin.branches.store');
    Route::get('/admin/branches', [BranchController::class, 'index'])->name('admin.branches');
    Route::post('/admin/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('/admin/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');
    Route::post('/admin/users/permissions', [BranchController::class, 'storePermissions'])
        ->name('admin.permissions.store');
    // Announcement Routes
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('admin/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('admin/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});


// Website Routes
Route::view('/', 'user.customer_form')->name('customer.form');

require __DIR__ . '/auth.php';

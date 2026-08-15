<?php

use App\Http\Controllers\Admin\CaptainController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth', function () {
    return view('auth/userlogin');
});

Route::get('/roadmap', function () {
    return view('roadmap');
})->name('roadmap');

Route::get('/dashboard', function () {
    return view('Admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('roles', RoleController::class)
        ->except(['show'])
        ->middleware('permission:roles.manage');

    Route::get('settings', [SettingController::class, 'edit'])
        ->name('settings.edit')
        ->middleware('permission:settings.manage');
    Route::put('settings', [SettingController::class, 'update'])
        ->name('settings.update')
        ->middleware('permission:settings.manage');

    // Fine-grained write checks (stores.manage / stores.approve) happen
    // inside StoreController — stores.view covers listing/viewing here.
    Route::resource('stores', StoreController::class)
        ->except(['show'])
        ->middleware('permission:stores.view');
    Route::post('stores/{store}/approve', [StoreController::class, 'approve'])->name('stores.approve');
    Route::post('stores/{store}/reject', [StoreController::class, 'reject'])->name('stores.reject');

    Route::resource('captains', CaptainController::class)
        ->except(['show'])
        ->parameters(['captains' => 'captain'])
        ->middleware('permission:captains.manage');
});

require __DIR__.'/auth.php';

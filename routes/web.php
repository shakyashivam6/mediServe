<?php

use App\Http\Controllers\Admin\CaptainController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Captain\CollectionReportController as CaptainCollectionReportController;
use App\Http\Controllers\Captain\DashboardController as CaptainDashboardController;
use App\Http\Controllers\Captain\DeliveryController as CaptainDeliveryController;
use App\Http\Controllers\Customer\Auth\OtpAuthController as CustomerOtpAuthController;
use App\Http\Controllers\Customer\PrescriptionController as CustomerPrescriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Store\CaptainController as StoreCaptainController;
use App\Http\Controllers\Store\CodSettlementController as StoreCodSettlementController;
use App\Http\Controllers\Store\DashboardController as StoreDashboardController;
use App\Http\Controllers\Store\PrescriptionController as StorePrescriptionController;
use App\Http\Controllers\Store\ProfileController as StoreProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/xt', function () {
    return view('welcome');
});

// Was pointed at the raw HighDmin demo page (auth/userlogin.blade.php) — a
// static, unwired scaffold with no real form action, which is why signup
// looked broken from here: its "Sign Up!" link goes to a non-existent
// auth-register.html rather than the real customer OTP flow. The actual,
// wired login page (with the working "customer login/sign up" link) lives
// at auth.login — point root there instead of duplicating it.
Route::middleware('guest')->get('/', [AuthenticatedSessionController::class, 'create'])->name('home');

Route::get('/roadmap', function () {
    return view('roadmap');
})->name('roadmap');

Route::get('/customer-requirements', function () {
    return view('customer-requirements');
})->name('customer-requirements');

Route::get('/dashboard', function () {
    // A Store/Captain/Customer account only ever gets its own panel — this
    // guards a stale bookmark/link, since the post-login redirect already
    // sends each one to its own area directly (store.dashboard /
    // captain.dashboard / customer.prescriptions.index).
    if (auth()->user()->role === 'store') {
        return redirect()->route('store.dashboard');
    }

    if (auth()->user()->role === 'captain') {
        return redirect()->route('captain.dashboard');
    }

    if (auth()->user()->role === 'customer') {
        return redirect()->route('customer.prescriptions.index');
    }

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

    Route::middleware('permission:catalog.manage')->prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('import', [ProductController::class, 'import'])->name('import');
        Route::post('import', [ProductController::class, 'processImport'])->name('import.store');
        Route::post('{product}/toggle-rx', [ProductController::class, 'toggleRx'])->name('toggle-rx');
        Route::post('{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('toggle-active');
    });
});

// Store's own self-service panel — separate from /admin entirely. A Store
// account never gets Spatie roles/permissions (see project memory), so
// access here is gated by plain account_role, not permission:.
Route::middleware(['auth', 'verified', 'account_role:store', 'store_profile_exists'])->prefix('store')->name('store.')->group(function () {
    Route::get('dashboard', [StoreDashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [StoreProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [StoreProfileController::class, 'update'])->name('profile.update');

    Route::resource('captains', StoreCaptainController::class)
        ->except(['show'])
        ->parameters(['captains' => 'captain']);

    // Shared queue: any approved Store can see + claim an unclaimed
    // Prescription; PrescriptionController enforces per-row ownership
    // beyond that (see its authorizeVisible/authorizeOwned).
    Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
        Route::get('/', [StorePrescriptionController::class, 'index'])->name('index');
        Route::get('{prescription}', [StorePrescriptionController::class, 'show'])->name('show');
        Route::post('{prescription}/claim', [StorePrescriptionController::class, 'claim'])->name('claim');
        Route::put('{prescription}', [StorePrescriptionController::class, 'update'])->name('update');
        Route::post('{prescription}/assign-captain', [StorePrescriptionController::class, 'assignCaptain'])->name('assign-captain');
        Route::get('{prescription}/files/{index}', [StorePrescriptionController::class, 'file'])->name('file');
        Route::get('{prescription}/messages', [StorePrescriptionController::class, 'messages'])->name('messages.index');
        Route::post('{prescription}/messages', [StorePrescriptionController::class, 'sendMessage'])->name('messages.store');
    });

    // COD cash hand-off: a Captain collects cash at the door (Captain\
    // DeliveryController), this is where the Store confirms it actually
    // got physically handed back and clears it off the Captain's balance.
    Route::prefix('settlements')->name('settlements.')->group(function () {
        Route::get('/', [StoreCodSettlementController::class, 'index'])->name('index');
        Route::post('{captain}/settle', [StoreCodSettlementController::class, 'settle'])->name('settle');
    });

    // See NotificationController — shared with the Captain/Customer groups
    // below, one controller for all three since reading/marking-read a
    // notification is identical regardless of role.
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('{notification}/open', [NotificationController::class, 'open'])->name('open');
        Route::post('mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });
});

// Captain's own panel — same account_role-gated pattern as Store's, no
// Spatie permissions involved (see project memory: store-self-service-
// panel). A Captain only ever sees its own assigned deliveries.
Route::middleware(['auth', 'verified', 'account_role:captain'])->prefix('captain')->name('captain.')->group(function () {
    Route::get('dashboard', [CaptainDashboardController::class, 'index'])->name('dashboard');
    Route::get('collections', [CaptainCollectionReportController::class, 'index'])->name('collections.index');

    Route::post('deliveries/{prescription}/deliver', [CaptainDeliveryController::class, 'deliver'])->name('deliveries.deliver');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('{notification}/open', [NotificationController::class, 'open'])->name('open');
        Route::post('mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });
});

// Customer — OTP-only login (see project memory: customer-auth-and-admin-
// driven-signup), own layout, not the Admin/Store HighDmin panel.
Route::middleware('guest')->prefix('customer')->name('customer.')->group(function () {
    Route::get('login', [CustomerOtpAuthController::class, 'showStart'])->name('login');
    Route::post('login', [CustomerOtpAuthController::class, 'start'])->name('login.start');
    Route::get('otp', [CustomerOtpAuthController::class, 'showVerify'])->name('otp.show');
    Route::post('otp', [CustomerOtpAuthController::class, 'verify'])->name('otp.verify');
    Route::post('otp/resend', [CustomerOtpAuthController::class, 'resend'])->name('otp.resend');
});

Route::middleware(['auth', 'account_role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('prescriptions', [CustomerPrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('prescriptions/create', [CustomerPrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('prescriptions', [CustomerPrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('prescriptions/{prescription}', [CustomerPrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::post('prescriptions/{prescription}/accept', [CustomerPrescriptionController::class, 'accept'])->name('prescriptions.accept');
    Route::post('prescriptions/{prescription}/reject', [CustomerPrescriptionController::class, 'reject'])->name('prescriptions.reject');
    Route::get('prescriptions/{prescription}/files/{index}', [CustomerPrescriptionController::class, 'file'])->name('prescriptions.file');
    Route::get('prescriptions/{prescription}/messages', [CustomerPrescriptionController::class, 'messages'])->name('prescriptions.messages.index');
    Route::post('prescriptions/{prescription}/messages', [CustomerPrescriptionController::class, 'sendMessage'])->name('prescriptions.messages.store');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('{notification}/open', [NotificationController::class, 'open'])->name('open');
        Route::post('mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });
});

require __DIR__.'/auth.php';

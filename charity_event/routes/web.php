<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', fn() => redirect()->route('home'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Debug session
Route::get('/test-auth', function () {
    return Auth::check()
        ? 'Đã login: ' . Auth::user()->email
        : 'Chưa login';
});
Route::get('/check-cookie', function () {
    return response()->json([
        'cookies' => request()->cookie(),
        'session_id' => session()->getId(),
        'user' => Auth::check() ? Auth::user()->email : null
    ]);
});

// ------------------------
// Routes cho Donor
// ------------------------
Route::middleware(['auth', RoleMiddleware::class . ':donor'])
    ->prefix('donor')
    ->group(function () {
        Route::get('/index', [DonorController::class, 'index'])->name('dn_index');
        Route::post('/update-info', [DonorController::class, 'updateInfo'])->name('donor.updateInfo');
        Route::post('/change-password', [DonorController::class, 'changePassword'])->name('donor.changePassword');

        Route::get('/event/{id}', [DonorController::class, 'showEventDetails'])->name('donor.event.details');

        Route::post('/event/comment', [DonorController::class, 'submitComment'])->name('donor.comment');

        Route::post('/event/donate', [DonorController::class, 'confirmDonation'])->name('donor.confirmDonation');
    });

// ------------------------
// Routes cho Organization
// ------------------------
Route::middleware(['auth', RoleMiddleware::class . ':organization'])
    ->prefix('organization')
    ->group(function () {
        Route::get('/index', [OrganizationController::class, 'index'])->name('org_index');
        Route::post('/notifications/mark-read', [OrganizationController::class, 'markNotificationsRead'])->name('organization.notifications.markRead');

        Route::get('/event/{id}', [OrganizationController::class, 'showEventDetails'])->name('organization.event.details');
        Route::post('/event/create', [OrganizationController::class, 'createEvent'])->name('organization.createEvent');
        Route::delete('/event/{id}', [OrganizationController::class, 'deleteEvent'])->name('organization.event.delete');

        Route::get('/event/{id}/request-edit', [OrganizationController::class, 'requestEditForm'])->name('organization.event.requestEditForm');
        Route::post('/event/request-edit', [OrganizationController::class, 'submitEditRequest'])->name('organization.event.requestEdit');

        Route::post('/update-info', [OrganizationController::class, 'updateInfo'])->name('organization.updateInfo');
        Route::post('/change-password', [OrganizationController::class, 'changePassword'])->name('organization.changePassword');
    });

// ------------------------
// Routes cho Admin
// ------------------------
Route::middleware(['auth', RoleMiddleware::class . ':admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/index', [AdminController::class, 'index'])->name('ad_index');
        Route::get('/event/{id}', [AdminController::class, 'showEventDetails'])->name('admin.event.details');
        Route::post('/event/{id}/edit/handle', [AdminController::class, 'approveEdit'])->name('admin.event.approveEdit');
        Route::delete('/event/{id}', [AdminController::class, 'deleteEvent'])->name('admin.event.delete');
    });

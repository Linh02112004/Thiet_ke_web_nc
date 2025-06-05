<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', function () {
    return view('home');
})->name('home');

// Hiển thị form đăng nhập (trong home có popup login)
Route::get('/login', function () {
    return view('home');
})->name('login.form');

// Xử lý đăng ký và đăng nhập
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Đăng xuất (nên thêm route logout)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Các route cho người quyên góp (donor)
Route::middleware(['auth', 'role:donor'])->prefix('donor')->group(function () {
    Route::get('/', [DonorController::class, 'index'])->name('donor.dashboard');
    Route::post('/update-info', [DonorController::class, 'updateInfo'])->name('donor.updateInfo');
    Route::post('/change-password', [DonorController::class, 'changePassword'])->name('donor.changePassword');
});

// Các route cho tổ chức (organization)
Route::middleware(['auth', 'role:organization'])->prefix('organization')->group(function () {
    Route::get('/index', [OrganizationController::class, 'index'])->name('organization.index');
    Route::get('/event/{id}', [OrganizationController::class, 'showEvent'])->name('organization.event.details');
    Route::get('/event/{id}/request-edit', [OrganizationController::class, 'requestEdit'])->name('organization.requestEdit');
    Route::delete('/event/{id}', [OrganizationController::class, 'deleteEvent'])->name('organization.event.delete');
});

// Các route cho admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
});

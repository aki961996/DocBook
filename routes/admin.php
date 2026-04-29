<?php

// routes/admin.php
// Include this in routes/web.php:
//   require __DIR__ . '/admin.php';

use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// ── Admin Auth (guard: admin) ──────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Public (guest) routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login',          [AuthController::class, 'showLogin'])->name('login');
        Route::post('login',         [AuthController::class, 'login']);
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected routes (auth:admin middleware)
    Route::middleware('auth:admin')->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ── Hospitals (super_admin only for create/delete) ──
        Route::resource('hospitals', HospitalController::class)
             ->except(['show']);
        Route::post('hospitals/{hospital}/toggle-status',
            [HospitalController::class, 'toggleStatus']
        )->name('hospitals.toggle-status');

        // ── Departments ─────────────────────────────────────
        Route::resource('departments', DepartmentController::class)
             ->except(['show']);

        // ── Doctors ─────────────────────────────────────────
        Route::resource('doctors', DoctorController::class)
             ->except(['show']);
        // AJAX: load departments when hospital changes in form
        Route::get('hospitals/{hospital}/departments',
            [DoctorController::class, 'departmentsByHospital']
        )->name('hospitals.departments');

        // ── Slots ────────────────────────────────────────────
        Route::prefix('doctors/{doctor}/slots')->name('slots.')->group(function () {
            Route::get('/',           [SlotController::class, 'index'])->name('index');
            Route::post('bulk',       [SlotController::class, 'bulkCreate'])->name('bulk');
            Route::delete('{slot}',   [SlotController::class, 'destroy'])->name('destroy');
        });
        Route::post('slots/{slot}/toggle-block',
            [SlotController::class, 'toggleBlock']
        )->name('slots.toggle-block');

        // ── Bookings ─────────────────────────────────────────
        Route::get('bookings',                 [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}',        [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/status',
            [BookingController::class, 'updateStatus']
        )->name('bookings.update-status');
    });
});

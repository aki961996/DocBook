<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\HospitalController;
use App\Http\Controllers\Web\DoctorController;
use App\Http\Controllers\Web\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Auth ──────────────────────────────────────────────
Route::middleware('guest:web')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
});

// OTP routes — no guest middleware (needed for resend too)
Route::post('otp/send',   [AuthController::class, 'sendOtp'])->name('otp.send');
Route::post('otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('logout',     [AuthController::class, 'logout'])->name('logout')
     ->middleware('auth:web');

// ── Public ────────────────────────────────────────────
Route::get('hospitals',              [HospitalController::class, 'index'])->name('hospitals.index');
Route::get('hospitals/{hospital}',   [HospitalController::class, 'show'])->name('hospitals.show');
Route::get('doctors',                [DoctorController::class, 'index'])->name('doctors.index');
Route::get('doctors/{doctor}',       [DoctorController::class, 'show'])->name('doctors.show');

// ── Auth required ─────────────────────────────────────
Route::middleware('auth:web')->group(function () {
    Route::get('bookings',  [BookingController::class, 'index'])->name('bookings.index');
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
});
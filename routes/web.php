<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Members
    Route::resource('members', MemberController::class);

    // Subscriptions (nested under member for create; standalone for cancel)
    Route::get('/members/{member}/subscriptions/create', [SubscriptionController::class, 'create'])
         ->name('subscriptions.create');
    Route::post('/members/{member}/subscriptions',       [SubscriptionController::class, 'store'])
         ->name('subscriptions.store');
    Route::patch('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
         ->name('subscriptions.cancel');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
         ->name('payments.receipt');

    // Attendance
    Route::get('/attendance',         [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance',        [AttendanceController::class, 'store'])->name('attendance.store');

    // Trainers
    Route::resource('trainers', TrainerController::class)->except(['show']);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',           [ReportController::class, 'index'])->name('index');
        Route::get('/revenue',    [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/members',    [ReportController::class, 'members'])->name('members');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
    });
});

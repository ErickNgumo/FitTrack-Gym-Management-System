<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PortalAccessController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\Member\MemberAuthController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Trainer\TrainerAuthController;
use App\Http\Controllers\Trainer\TrainerDashboardController;
use Illuminate\Support\Facades\Route;

// ════════════════════════════════════════════════════════════
// ADMIN / STAFF PORTAL  (guard: web)
// ════════════════════════════════════════════════════════════

// Landing page – portal selection
Route::get('/', fn() => view('welcome'))->name('home');

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('members', MemberController::class);

    Route::get('/members/{member}/subscriptions/create', [SubscriptionController::class, 'create'])
         ->name('subscriptions.create');
    Route::post('/members/{member}/subscriptions', [SubscriptionController::class, 'store'])
         ->name('subscriptions.store');
    Route::patch('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
         ->name('subscriptions.cancel');

    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
         ->name('payments.receipt');

    Route::get('/attendance',         [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance',        [AttendanceController::class, 'store'])->name('attendance.store');

    Route::resource('trainers', TrainerController::class)->except(['show']);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',           [ReportController::class, 'index'])->name('index');
        Route::get('/revenue',    [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/members',    [ReportController::class, 'members'])->name('members');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
    });

    // Portal access management
    Route::prefix('admin/portal-access')->name('portal.')->group(function () {
        Route::get('/members',                          [PortalAccessController::class, 'memberIndex'])->name('members');
        Route::post('/members/{member}/grant',          [PortalAccessController::class, 'memberGrant'])->name('members.grant');
        Route::post('/members/{member}/reset',          [PortalAccessController::class, 'memberReset'])->name('members.reset');
        Route::post('/members/{member}/revoke',         [PortalAccessController::class, 'memberRevoke'])->name('members.revoke');
        Route::get('/trainers',                         [PortalAccessController::class, 'trainerIndex'])->name('trainers');
        Route::post('/trainers/{trainer}/grant',        [PortalAccessController::class, 'trainerGrant'])->name('trainers.grant');
        Route::post('/trainers/{trainer}/reset',        [PortalAccessController::class, 'trainerReset'])->name('trainers.reset');
        Route::post('/trainers/{trainer}/revoke',       [PortalAccessController::class, 'trainerRevoke'])->name('trainers.revoke');
    });
});


// ════════════════════════════════════════════════════════════
// MEMBER PORTAL  (guard: member)   /member/*
// ════════════════════════════════════════════════════════════

Route::prefix('member')->name('member.')->group(function () {

    Route::get('/login',  [MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [MemberAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[MemberAuthController::class, 'logout'])->name('logout')->middleware('auth:member');

    Route::middleware(['auth:member'])->group(function () {
        Route::get('/change-password',  [MemberAuthController::class, 'showChangePassword'])->name('change-password');
        Route::post('/change-password', [MemberAuthController::class, 'changePassword'])->name('change-password.post');

        Route::get('/dashboard',          [MemberDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/attendance',         [MemberDashboardController::class, 'attendance'])->name('attendance');
        Route::get('/payments',           [MemberDashboardController::class, 'payments'])->name('payments');
        Route::get('/subscriptions',      [MemberDashboardController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/workouts',           [MemberDashboardController::class, 'workouts'])->name('workouts');
        Route::get('/workouts/{session}', [MemberDashboardController::class, 'workoutShow'])->name('workout.show');
        Route::post('/workouts/{session}/feedback', [MemberDashboardController::class, 'submitFeedback'])
             ->name('workout.feedback');
    });
});


// ════════════════════════════════════════════════════════════
// TRAINER PORTAL  (guard: trainer)  /trainer/*
// ════════════════════════════════════════════════════════════

Route::prefix('trainer')->name('trainer.')->group(function () {

    Route::get('/login',  [TrainerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [TrainerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[TrainerAuthController::class, 'logout'])->name('logout')->middleware('auth:trainer');

    Route::middleware(['auth:trainer'])->group(function () {
        Route::get('/change-password',  [TrainerAuthController::class, 'showChangePassword'])->name('change-password');
        Route::post('/change-password', [TrainerAuthController::class, 'changePassword'])->name('change-password.post');

        Route::get('/dashboard',         [TrainerDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/members',           [TrainerDashboardController::class, 'members'])->name('members');
        Route::get('/members/{member}',  [TrainerDashboardController::class, 'memberShow'])->name('member.show');

        Route::get('/members/{member}/sessions/create', [TrainerDashboardController::class, 'sessionCreate'])
             ->name('session.create');
        Route::post('/members/{member}/sessions', [TrainerDashboardController::class, 'sessionStore'])
             ->name('session.store');
        Route::get('/sessions/{session}', [TrainerDashboardController::class, 'sessionShow'])
             ->name('session.show');

        Route::post('/members/{member}/notes',  [TrainerDashboardController::class, 'noteStore'])->name('note.store');
        Route::delete('/notes/{note}',          [TrainerDashboardController::class, 'noteDestroy'])->name('note.destroy');
    });
});
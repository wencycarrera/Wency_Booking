<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingViewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;

// Public Homepage
Route::get('/', function () {
    return view('welcome');
});

// Guest-only: Registration
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Booking Management
   Route::resource('bookings', BookingViewController::class)->names([
    'index' => 'bookings.index',
    'create' => 'bookings.create',
    'store' => 'bookings.store',
    'show' => 'bookings.show',
    'edit' => 'bookings.edit',
    'update' => 'bookings.update',
    'destroy' => 'bookings.destroy',
]);

Route::middleware(['auth'])->group(function () {
    // Add this route for admin viewing
    Route::get('/admin/bookings', [BookingViewController::class, 'allBookings'])->name('admin.bookings');
});

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::post('/notifications/mark-all-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
    })->name('notifications.markAllRead');


    // Admin-only User Management
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
});

// Auth routes (login, logout, etc.)
require __DIR__.'/auth.php';

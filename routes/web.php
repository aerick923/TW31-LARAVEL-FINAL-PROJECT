
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipApplicationController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// About page
Route::get('/about', [PageController::class, 'about']);

// Task Manager
Route::get('/tasks/{id}', [PageController::class, 'task']);

Route::get('/tasks', [TaskController::class, 'index'])
    ->name('tasks.index');

Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');

// Member Management
Route::resource('members', MemberController::class);

Route::patch(
    '/members/{member}/toggle-status',
    [MemberController::class, 'toggleStatus']
)->name('members.toggleStatus');

// Public Membership Application
Route::get(
    '/membership/apply',
    [MembershipApplicationController::class, 'create']
)->name('membership.create');

Route::post(
    '/membership/apply',
    [MembershipApplicationController::class, 'store']
)->name('membership.store');

// Live Weather
Route::get('/weather', [WeatherController::class, 'show'])
    ->name('weather.show');

// Transaction Processing System (TPS)

// Display all transactions
Route::get('/transactions', [TransactionController::class, 'index'])
    ->name('transactions.index');

// Show transaction creation form
Route::get('/transactions/create', [TransactionController::class, 'create'])
    ->name('transactions.create');

// Store new membership or renewal transaction
Route::post('/transactions', [TransactionController::class, 'store'])
    ->name('transactions.store');

// View transaction history for a member
Route::get(
    '/members/{member}/transactions',
    [TransactionController::class, 'history']
)->name('transactions.history');

// View transaction details
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
    ->name('transactions.show');

// Approve a fully paid transaction
Route::patch(
    '/transactions/{transaction}/approve',
    [TransactionController::class, 'approve']
)->name('transactions.approve');

// Cancel a pending transaction
Route::patch(
    '/transactions/{transaction}/cancel',
    [TransactionController::class, 'cancel']
)->name('transactions.cancel');

// Payment Processing System

// Show payment recording form
Route::get(
    '/transactions/{transaction}/payments/create',
    [PaymentController::class, 'create']
)->name('payments.create');

// Record a payment for a transaction
Route::post(
    '/transactions/{transaction}/payments',
    [PaymentController::class, 'store']
)->name('payments.store');


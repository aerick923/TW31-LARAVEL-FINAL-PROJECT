
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipApplicationController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

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
    ->middleware('throttle:60,1')
    ->name('weather.show');

/*
|--------------------------------------------------------------------------
| ADMINISTRATOR AUTHENTICATION
|--------------------------------------------------------------------------
*/

// Display login page
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

// Process login credentials
Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('login.submit');

// Log out authenticated administrator
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ADMINISTRATOR ROUTES
|--------------------------------------------------------------------------
|
| Only authenticated users with the admin role can access these routes.
|
*/

Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | MEMBER MANAGEMENT
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | MEMBERSHIP CATEGORY MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource('categories', CategoryController::class)
        ->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | MEMBERSHIP LEVEL MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource('levels', LevelController::class)
        ->except(['show']);



    // Member CRUD
    Route::resource('members', MemberController::class);

    // Activate or deactivate member
    Route::patch(
        '/members/{member}/toggle-status',
        [MemberController::class, 'toggleStatus']
    )->name('members.toggleStatus');

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION PROCESSING SYSTEM
    |--------------------------------------------------------------------------
    */

    // Display all transactions
    Route::get(
        '/transactions',
        [TransactionController::class, 'index']
    )->name('transactions.index');

    // Show transaction creation form
    Route::get(
        '/transactions/create',
        [TransactionController::class, 'create']
    )->name('transactions.create');

    // Store new membership or renewal transaction
    Route::post(
        '/transactions',
        [TransactionController::class, 'store']
    )->name('transactions.store');

    // View transaction history for a member
    Route::get(
        '/members/{member}/transactions',
        [TransactionController::class, 'history']
    )->name('transactions.history');

    // View transaction details
    Route::get(
        '/transactions/{transaction}',
        [TransactionController::class, 'show']
    )->name('transactions.show');

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

    /*
    |--------------------------------------------------------------------------
    | PAYMENT PROCESSING
    |--------------------------------------------------------------------------
    */

    // Show payment recording form
    Route::get(
        '/transactions/{transaction}/payments/create',
        [PaymentController::class, 'create']
    )->name('payments.create');

    // Record payment
    Route::post(
        '/transactions/{transaction}/payments',
        [PaymentController::class, 'store']
    )->name('payments.store');

});

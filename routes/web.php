
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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\AdminMembershipApplicationController;

use App\Http\Controllers\UserMembershipController;
use App\Http\Controllers\UserTaskController;


/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return view('welcome');
});

// About page
Route::get('/about', [PageController::class, 'about']);




/*
|--------------------------------------------------------------------------
| VERIFIED USER MEMBERSHIP APPLICATION
|--------------------------------------------------------------------------
|
| Only authenticated, verified regular users can apply.
|
*/

Route::middleware(['auth', 'regular', 'verified'])->group(function () {


    // User membership details page.
    Route::get(
        '/user/membership',
        [UserMembershipController::class, 'membership']
    )->name('user.membership');

    // User membership application history.
    Route::get(
        '/user/applications',
        [UserMembershipController::class, 'applications']
    )->name('user.applications');

    // User transaction and payment history.
    Route::get(
        '/user/transactions',
        [UserMembershipController::class, 'transactions']
    )->name('user.transactions');


    // Display membership application form.
    Route::get(
        '/membership/apply',
        [MembershipApplicationController::class, 'create']
    )->name('membership.create');

    // Submit membership application.
    Route::post(
        '/membership/apply',
        [MembershipApplicationController::class, 'store']
    )->middleware('throttle:5,1')
     ->name('membership.store');

});


// Live Weather
Route::get('/weather', [WeatherController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('weather.show');


/*
|--------------------------------------------------------------------------
| 2. USER REGISTRATION
|--------------------------------------------------------------------------
*/

// Display registration page
Route::get(
    '/register',
    [RegisterController::class, 'showRegistration']
)
    ->middleware('guest')
    ->name('register');

// Process registration
Route::post(
    '/register',
    [RegisterController::class, 'register']
)
    ->middleware(['guest', 'throttle:5,1'])
    ->name('register.submit');


/*
|--------------------------------------------------------------------------
| 3. USER AND ADMINISTRATOR AUTHENTICATION
|--------------------------------------------------------------------------
*/

// Display login page
Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)
    ->middleware('guest')
    ->name('login');

// Process login
Route::post(
    '/login',
    [AuthController::class, 'login']
)
    ->middleware(['guest', 'throttle:5,1'])
    ->name('login.submit');

// Log out authenticated account
Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| 4. EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/

// Display verification notice
Route::get(
    '/email/verify',
    [EmailVerificationController::class, 'notice']
)
    ->middleware(['auth', 'regular'])
    ->name('verification.notice');

// Verify signed email link
Route::get(
    '/email/verify/{id}/{hash}',
    [EmailVerificationController::class, 'verify']
)
    ->middleware([
        'auth',
        'regular',
        'signed',
        'throttle:6,1',
    ])
    ->name('verification.verify');

// Resend verification email
Route::post(
    '/email/verification-notification',
    [EmailVerificationController::class, 'resend']
)
    ->middleware([
        'auth',
        'regular',
        'throttle:6,1',
    ])
    ->name('verification.send');


/*
|--------------------------------------------------------------------------
| 5. PROTECTED REGULAR USER ROUTES
|--------------------------------------------------------------------------
|
| Only authenticated, email-verified regular users
| can access these pages.
|
*/

Route::middleware([
    'auth',
    'regular',
    'verified',
])->group(function () {

    // Allow regular users to view only their own receipts.
    Route::get(
        '/user/transactions/{transaction}/receipt',
        [ReceiptController::class, 'showUserReceipt']
    )->name('user.transactions.receipt');

Route::get('/user/tasks', [UserTaskController::class, 'index'])
    ->name('user.tasks.index');


Route::patch(
    '/user/tasks/{task}/complete',
    [UserTaskController::class, 'complete']
)->name('user.tasks.complete');

    
    // User Dashboard
    Route::get('/user/dashboard', function () {

        $user = auth()->user();

        // Retrieve only applications belonging to this user.
        $applications = \App\Models\MembershipApplication::query()
            ->where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->get();

        // Find the member record linked to an approved application.
        $approvedApplication = $applications
            ->first(function ($application) {
                return $application->status === 'approved'
                    && $application->member_id !== null;
            });

        $member = null;
        $transactions = collect();

        if ($approvedApplication) {

            // Confirm the member is linked to this user's application.
            $member = \App\Models\Member::query()
                ->whereKey($approvedApplication->member_id)
                ->first();

            if ($member) {

                // Retrieve transactions for this member.
                $transactions = \App\Models\MembershipTransaction::query()
                    ->where('member_id', $member->id)
                    ->with('payments')
                    ->latest()
                    ->get();
            }
        }

        return view('user.dashboard', [
            'applications' => $applications,
            'member' => $member,
            'transactions' => $transactions,
        ]);

    })->name('user.dashboard');



});


/*
|--------------------------------------------------------------------------
| 6. PROTECTED ADMINISTRATOR ROUTES
|--------------------------------------------------------------------------
|
| Only authenticated users with the admin role
| can access these routes.
|
*/

Route::middleware(['auth', 'admin'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATOR TASK MANAGEMENT
    |--------------------------------------------------------------------------
    */

    // Display all tasks.
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // Create a task.
    Route::post('/tasks', [TaskController::class, 'store'])
        ->name('tasks.store');


    // Display the task editing form.
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
        ->name('tasks.edit');

    // Update an existing task.
    Route::put('/tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update');

    // Delete an existing task.
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy');




    // Administrator Membership Applications
    Route::get(
        '/admin/membership-applications',
        [AdminMembershipApplicationController::class, 'index']
    )->name('admin.applications.index');


    // Approve or reject a membership application.
    Route::patch(
        '/admin/membership-applications/{application}/status',
        [AdminMembershipApplicationController::class, 'updateStatus']
    )->name('admin.applications.update-status');


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATOR DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard.index');


    /*
    |--------------------------------------------------------------------------
    | MEMBER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    // Member CRUD
    Route::resource('members', MemberController::class);

    // Activate or deactivate member
    Route::patch(
        '/members/{member}/toggle-status',
        [MemberController::class, 'toggleStatus']
    )->name('members.toggleStatus');


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


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION PROCESSING
    |--------------------------------------------------------------------------
    */

    // Display all transactions
    Route::get(
        '/transactions',
        [TransactionController::class, 'index']
    )->name('transactions.index');

    // Create transaction
    Route::get(
        '/transactions/create',
        [TransactionController::class, 'create']
    )->name('transactions.create');

    // Store transaction
    Route::post(
        '/transactions',
        [TransactionController::class, 'store']
    )->name('transactions.store');

    // Member transaction history
    Route::get(
        '/members/{member}/transactions',
        [TransactionController::class, 'history']
    )->name('transactions.history');

    // View transaction details
    Route::get(
        '/transactions/{transaction}',
        [TransactionController::class, 'show']
    )->name('transactions.show');

    // Approve transaction
    Route::patch(
        '/transactions/{transaction}/approve',
        [TransactionController::class, 'approve']
    )->name('transactions.approve');

    // Cancel transaction
    Route::patch(
        '/transactions/{transaction}/cancel',
        [TransactionController::class, 'cancel']
    )->name('transactions.cancel');


    /*
    |--------------------------------------------------------------------------
    | PAYMENT PROCESSING
    |--------------------------------------------------------------------------
    */

    // Show payment form
    Route::get(
        '/transactions/{transaction}/payments/create',
        [PaymentController::class, 'create']
    )->name('payments.create');

    // Record payment
    Route::post(
        '/transactions/{transaction}/payments',
        [PaymentController::class, 'store']
    )->name('payments.store');


    /*
    |--------------------------------------------------------------------------
    | PRINTABLE RECEIPTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/transactions/{transaction}/receipt',
        [ReceiptController::class, 'show']
    )->name('transactions.receipt');

});

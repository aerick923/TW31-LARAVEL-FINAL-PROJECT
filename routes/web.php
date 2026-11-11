<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipApplicationController;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', [PageController::class, 'about']);

Route::get('/tasks/{id}', [PageController::class, 'task']);

Route::get('/tasks', [TaskController::class, 'index'])
    ->name('tasks.index');

Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');

Route::resource('members', MemberController::class);

Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])
    ->name('members.toggleStatus');


Route::get('/membership/apply', [MembershipApplicationController::class, 'create'])
    ->name('membership.create');

Route::post('/membership/apply', [MembershipApplicationController::class, 'store'])
    ->name('membership.store');

Route::get('/weather', [WeatherController::class, 'show'])
    ->name('weather.show');

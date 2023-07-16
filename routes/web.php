<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

Route::view('/', 'auth/login')->name('login.page');

Route::group(['middleware' => 'auth'], function () {
    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('admin/feminine-list', [AdminController::class, 'feminineList']);
        Route::get('admin/feminine-data', [AdminController::class, 'feminineData']);
        Route::post('admin/new-feminine', [AdminController::class, 'postFeminine']);
        Route::post('admin/confirm-feminine', [AdminController::class, 'confirmFeminine']);
        Route::post('admin/update-feminine', [AdminController::class, 'postFeminine']);
        Route::post('admin/delete-feminine', [AdminController::class, 'deleteFeminie']);
        Route::post('admin/post-seen/period-notification', [AdminController::class, 'postSeenPeriodNotification']);

        Route::get('admin/calendar', [AdminController::class, 'calendarIndex']);
        Route::get('admin/calendar-data', [AdminController::class, 'calendarData']);

        Route::get('admin/account-settings', [AdminController::class, 'accountSettings']);
        Route::get('admin/account-data', [AdminController::class, 'accountData']);
        Route::post('admin/account-reset', [AdminController::class, 'accountReset']);
    });

    // User Routes
    Route::middleware(['role:user'])->group(function () {
        Route::get('user/dashboard', [UserController::class, 'index'])->name('user.dashboard');

        Route::get('user/esimated-next-period', [UserController::class, 'estimatedNextPeriod']);
        Route::get('user/calendar/menstruation-periods', [UserController::class, 'menstruationCalendarPeriod']);

        Route::get('user/menstrual', [UserController::class, 'menstrualIndex']);
        Route::get('user/menstrual-data', [UserController::class, 'menstrualData']);
        Route::post('user/post-menstruation-period', [UserController::class, 'postMenstruationPeriod']);
        Route::post('user/update-menstruation-period', [UserController::class, 'updateMenstruationPeriod']);
        Route::post('user/delete-menstruation-period', [UserController::class, 'deleteMenstruationPeriod']);

        Route::get('user/profile', [UserController::class, 'profileIndex']);
        Route::post('user/update-profile', [UserController::class, 'updateProfile']);
        Route::post('user/change-password', [UserController::class, 'changePassword']);
    });
});


<?php

use App\Models\Assembly;
use App\Models\Resource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

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

// Donation Routes (all users)
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
Route::get('/donations/{donation}/confirm', [DonationController::class, 'confirm'])->name('donations.confirm');

// Routes for donations - accessible to everyone, no auth required
Route::post('/donations', [App\Http\Controllers\DonationController::class, 'store'])->name('donations.store');

// Routes for payment method validation
Route::post('/payment-methods/validate-phone', [App\Http\Controllers\PaymentMethodController::class, 'validatePhone'])
    ->name('payment-methods.validate-phone');

// Public callback route for FreeMoPay
Route::post('/donations/callback', [DonationController::class, 'callback'])->name('donations.callback');

Route::get('/dangerous-migrate-force', function () {
    Artisan::Call('migrate --force');
    return redirect(\route('dashboard'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Authenticated routes

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/storage-link', function () {
        Artisan::Call('storage:unlink');
        Artisan::Call('storage:link');
        return view('dashboard');
    });

    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/journal', function () {
        return view('journal');
    })->name('journal');

    Route::get('/profile', function () {
        return view('users.show', ['user' => Auth::user()]);
    })->name('profile');

    Route::get('/log', function () {
        return view('log');
    })->name('log');

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('admins', App\Http\Controllers\AdminController::class);
    Route::resource('events', App\Http\Controllers\EventController::class);
    Route::resource('groups', App\Http\Controllers\GroupController::class);
    Route::resource('sectors', App\Http\Controllers\SectorController::class);
    Route::resource('subsectors', App\Http\Controllers\SubsectorController::class);
    Route::resource('messages', App\Http\Controllers\MessageController::class);
    Route::resource('resources', App\Http\Controllers\ResourceController::class);
    Route::resource('medias', App\Http\Controllers\MediaController::class);
    Route::resource('assemblies', App\Http\Controllers\AssemblyController::class);

    Route::post('/assemblies/{assembly}/users', App\Http\Controllers\AddUserToAssemblyController::class)
        ->name('assemblies.users.store')->can('update', Assembly::class);

    Route::post('/resources/{resource}/users', App\Http\Controllers\BorrowedResourceController::class)
        ->name('resources.users.borrow')->can('update', Resource::class);

    // Reporting
    Route::group(['prefix' => '/report', 'as' => 'report.'], function () {
        Route::post('admins', App\Http\Controllers\GenerateAdminsReportController::class)
            ->name('admins');
        Route::post('donations', App\Http\Controllers\GenerateDonationsReportController::class)
            ->name('donations');
        Route::post('users', App\Http\Controllers\GenerateUsersReportController::class)
            ->name('users');
        Route::post('events', App\Http\Controllers\GenerateEventsReportController::class)
            ->name('events');
        Route::post('groups', App\Http\Controllers\GenerateGroupsReportController::class)
            ->name('groups');
        Route::post('sectors', App\Http\Controllers\GenerateSectorsReportController::class)
            ->name('sectors');
        Route::post('assemblies', App\Http\Controllers\GenerateAssembliesReportController::class)
            ->name('assemblies');
        Route::post('medias', App\Http\Controllers\GenerateMediasReportController::class)
            ->name('medias');
        Route::post('resources', App\Http\Controllers\GenerateResourcesReportController::class)
            ->name('resources');
        Route::post('messages', App\Http\Controllers\GenerateMessagesReportController::class)
            ->name('messages');
    });
});

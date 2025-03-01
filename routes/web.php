<?php

use App\Models\Assembly;
use App\Models\Resource;
use Illuminate\Support\Facades\Route;

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

Route::get('/dangerous-migrate-force', function () {
    Artisan::Call('migrate --force');
    return redirect(\route('dashboard'));
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

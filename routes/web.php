<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\BaptismController;
use App\Http\Controllers\BorrowedController;
use App\Http\Controllers\BorrowedResourceController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\SubsectorController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddUserToAssemblyController;
use App\Http\Controllers\LogController;
use App\Models\Assembly;
use App\Models\Resource;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

// Public routes - accessible without authentication
Route::get('/', function () {
    return Auth::check() ? redirect('dashboard') : redirect('login');
});

// In routes/web.php
Route::post('/', [DonationController::class, 'processDonation'])
    ->name('process')
    ->middleware('throttle:10,1'); // 10 submissions per minute

// Payment and donation routes with public access
// Donation routes
Route::prefix('donations')->name('donations.')->group(function () {
    // Show donation form
    Route::get('/list', [DonationController::class, 'index'])->name('index');

    // Show donation form
    Route::get('/', [DonationController::class, 'showDonationForm'])->name('form');

    // Process donation
    Route::post('/', [DonationController::class, 'processDonation'])->name('process');

    // Donation confirmation page
    Route::get('/confirmation/{id}', [DonationController::class, 'showConfirmation'])->name('confirmation');
    Route::get('/confirmation/{id}', [DonationController::class, 'showConfirmation'])->name('confirm');

    // FreeMoPay callback URL
    Route::post('/callback', [DonationController::class, 'handleCallback'])->name('callback');

    // Check donation status (AJAX)
    Route::post('/check-status', [DonationController::class, 'checkDonationStatus'])->name('check-status');

    // Cancel donation (AJAX)
    Route::post('/cancel', [DonationController::class, 'cancelDonation'])->name('cancel');

    // My donations (requires auth)
    Route::get('/my-donations', [DonationController::class, 'showMyDonations'])
        ->middleware('auth')
        ->name('my');
});

// Payment method validation endpoint
Route::post('/payment-methods/validate-phone', [PaymentMethodController::class, 'validatePhone'])
    ->name('payment-methods.validate-phone');

// Authentication required routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard and general pages
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/journal', function () {
        return view('journal');
    })->name('journal');

    // Journal des logs
    Route::get('journal', [LogController::class, 'index'])->name('journal');
    Route::get('journal/export', [LogController::class, 'export'])->name('journal.export');

    // API des logs
    Route::prefix('api')->group(function () {
        Route::get('logs/{id}', [LogController::class, 'show']);
        Route::get('logs/counts', [LogController::class, 'getCounts']);
    });

    Route::get('/profile', function () {
        return view('users.show', ['user' => Auth::user()]);
    })->name('profile');

    // Reports and export routes
    Route::prefix('reports')->name('reports.')->middleware(['auth'])->group(function () {
        Route::get('/model-data', [ReportController::class, 'getModelData'])->name('model-data');
        Route::get('/model-filters', [ReportController::class, 'getModelFilters'])->name('model-filters');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
    });

    // Resource management routes
    Route::resources([
        'users' => UserController::class,
        'admins' => AdminController::class,
        'events' => EventController::class,
        'groups' => GroupController::class,
        'sectors' => SectorController::class,
        'subsectors' => SubsectorController::class,
        'messages' => MessageController::class,
        'resources' => ResourceController::class,
        'medias' => MediaController::class,
        'assemblies' => AssemblyController::class,
    ]);

    // Protected donation routes (delete operations require auth)
    Route::resource('donations', DonationController::class)->only(['destroy']);

    // Custom relationships and actions
    Route::post('/assemblies/{assembly}/users', AddUserToAssemblyController::class)
        ->name('assemblies.users.store')
        ->can('update', Assembly::class);

    Route::post('/resources/{resource}/users', BorrowedResourceController::class)
        ->name('resources.users.borrow')
        ->can('update', Resource::class);
});

// Administrative routes (should be protected by admin middleware in a real app)
// Administrative routes - combine existing routes and add comprehensive update functionality
Route::middleware(['auth:sanctum'])->prefix('admin')->name('admin.')->group(function () {
    // System update route - enhanced with comprehensive deployment steps
    Route::get('/deploy-update-project', function () {
        try {
            // Start timing for performance monitoring
            $startTime = microtime(true);
            $log = [];

            // 1. Clear application caches
            $log[] = 'Clearing caches...';
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $log[] = '✓ Caches cleared successfully';

            // 2. Run migrations with force flag to skip confirmations
            $log[] = 'Running database migrations...';
            $migrationOutput = Artisan::call('migrate --force');
            $log[] = '✓ Migrations completed: ' . $migrationOutput;

            // 3. Run necessary seeders
            $log[] = 'Seeding database with required data...';
            Artisan::call('db:seed --class=RolesSeeder');
            Artisan::call('db:seed --class=PaymentMethodSeeder');
            $log[] = '✓ Seeders completed';

            // 4. Set up storage symlink if needed
            $log[] = 'Setting up storage links...';
            Artisan::call('storage:link');
            $log[] = '✓ Storage links created';

            // 5. Register scheduled commands for donation processing
            $log[] = 'Setting up scheduled jobs...';
            Artisan::call('schedule:run');
            $log[] = '✓ Scheduled jobs registered';

            // 6. Optimize the application
            $log[] = 'Optimizing application...';
            Artisan::call('optimize');
            $log[] = '✓ Application optimized';

            // Calculate execution time
            $executionTime = round(microtime(true) - $startTime, 2);
            $log[] = "Deployment completed in {$executionTime} seconds";

            // Log the successful deployment
            if (class_exists('App\Services\LoggingService')) {
                \App\Services\LoggingService::info(
                    'System deployment completed successfully',
                    [
                        'execution_time' => $executionTime,
                        'steps' => $log,
                        'user_id' => auth()->id()
                    ],
                    'system'
                );
            }

            // Return success with deployment log
            return view('admins.deployment', [
                'success' => true,
                'log' => $log,
                'executionTime' => $executionTime
            ]);
        } catch (\Exception $e) {
            // Log the error
            if (class_exists('App\Services\LoggingService')) {
                \App\Services\LoggingService::error(
                    'System deployment failed',
                    [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'user_id' => auth()->id()
                    ],
                    'system'
                );
            }

            // Return error response
            return view('admins.deployment', [
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('deploy-update');

    // Keep the existing routes
    Route::get('/dangerous-update-force', function () {
        Artisan::call('migrate --force');
        Artisan::call('db:seed');
        Artisan::call('db:seed --class=PaymentMethodSeeder');
        Artisan::call('db:seed --class=RolesSeeder');

        return redirect()->route('dashboard')->with('success', 'System updated successfully.');
    })->name('dangerous-update');

    // Storage link route - should be restricted to super admins
    Route::get('/storage-link', function () {
        Artisan::call('storage:unlink');
        Artisan::call('storage:link');

        return redirect()->route('dashboard')->with('success', 'Storage linked successfully.');
    })->name('storage-link');
});

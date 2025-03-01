<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\AdminPolicy;
use App\Policies\AssemblyPolicy;
use App\Policies\BaptismPolicy;
use App\Policies\MediaPolicy;
use App\Policies\EventPolicy;
use App\Policies\GroupPolicy;
use App\Policies\MessagePolicy;
use App\Policies\ResourcePolicy;
use App\Policies\RolePolicy;
use App\Policies\SectorPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin.create', [AdminPolicy::class, 'create']);
        Gate::define('admin.view', [AdminPolicy::class, 'view']);
        Gate::define('admin.viewAny', [AdminPolicy::class, 'viewAny']);
        Gate::define('admin.update', [AdminPolicy::class, 'update']);
        Gate::define('admin.delete', [AdminPolicy::class, 'delete']);
        Gate::define('admin.revoke', [AdminPolicy::class, 'delete']);
        Gate::define('admin.force-delete', [AdminPolicy::class, 'forceDelete']);
    }
}

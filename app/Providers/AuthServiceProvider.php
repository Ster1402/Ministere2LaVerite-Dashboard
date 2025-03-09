<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Roles;
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
use App\Policies\SubsectorPolicy;
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
        'App\Models\Group' => GroupPolicy::class,
        'App\Models\Message' => MessagePolicy::class,
        'App\Models\Resource' => ResourcePolicy::class,
        'App\Models\Media' => MediaPolicy::class,
        'App\Models\Assembly' => AssemblyPolicy::class,
        'App\Models\Event' => EventPolicy::class,
        'App\Models\Sector' => SectorPolicy::class,
        'App\Models\Subsector' => SubsectorPolicy::class,
        'App\Models\Transaction' => TransactionPolicy::class,
        'App\Models\Baptism' => BaptismPolicy::class,
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

        Gate::define('assembly.viewAny', [AssemblyPolicy::class, 'viewAny']);
        Gate::define('assembly.view', [AssemblyPolicy::class, 'view']);
        Gate::define('assembly.create', [AssemblyPolicy::class, 'create']);
        Gate::define('assembly.update', [AssemblyPolicy::class, 'update']);
        Gate::define('assembly.delete', [AssemblyPolicy::class, 'delete']);
        Gate::define('assembly.restore', [AssemblyPolicy::class, 'restore']);
        Gate::define('assembly.forceDelete', [AssemblyPolicy::class, 'forceDelete']);

        Gate::define('baptism.viewAny', [BaptismPolicy::class, 'viewAny']);
        Gate::define('baptism.view', [BaptismPolicy::class, 'view']);
        Gate::define('baptism.create', [BaptismPolicy::class, 'create']);
        Gate::define('baptism.update', [BaptismPolicy::class, 'update']);
        Gate::define('baptism.delete', [BaptismPolicy::class, 'delete']);

        Gate::define('event.viewAny', [EventPolicy::class, 'viewAny']);
        Gate::define('event.view', [EventPolicy::class, 'view']);
        Gate::define('event.create', [EventPolicy::class, 'create']);
        Gate::define('event.update', [EventPolicy::class, 'update']);
        Gate::define('event.delete', [EventPolicy::class, 'delete']);
        Gate::define('event.restore', [EventPolicy::class, 'restore']);

        Gate::define('sector.viewAny', [SectorPolicy::class, 'viewAny']);
        Gate::define('sector.view', [SectorPolicy::class, 'view']);
        Gate::define('sector.create', [SectorPolicy::class, 'create']);
        Gate::define('sector.update', [SectorPolicy::class, 'update']);
        Gate::define('sector.delete', [SectorPolicy::class, 'delete']);

        Gate::define('subsector.viewAny', [SubsectorPolicy::class, 'viewAny']);
        Gate::define('subsector.view', [SubsectorPolicy::class, 'view']);
        Gate::define('subsector.create', [SubsectorPolicy::class, 'create']);
        Gate::define('subsector.update', [SubsectorPolicy::class, 'update']);
        Gate::define('subsector.delete', [SubsectorPolicy::class, 'delete']);

        Gate::define('message.viewAny', [MessagePolicy::class, 'viewAny']);
        Gate::define('message.view', [MessagePolicy::class, 'view']);
        Gate::define('message.create', [MessagePolicy::class, 'create']);
        Gate::define('message.update', [MessagePolicy::class, 'update']);
        Gate::define('message.delete', [MessagePolicy::class, 'delete']);
        Gate::define('message.restore', [MessagePolicy::class, 'restore']);
        Gate::define('message.forceDelete', [MessagePolicy::class, 'forceDelete']);

        Gate::define('resource.viewAny', [ResourcePolicy::class, 'viewAny']);
        Gate::define('resource.view', [ResourcePolicy::class, 'view']);
        Gate::define('resource.create', [ResourcePolicy::class, 'create']);
        Gate::define('resource.update', [ResourcePolicy::class, 'update']);
        Gate::define('resource.delete', [ResourcePolicy::class, 'delete']);
        Gate::define('resource.restore', [ResourcePolicy::class, 'restore']);
        Gate::define('resource.forceDelete', [ResourcePolicy::class, 'forceDelete']);
    }
}

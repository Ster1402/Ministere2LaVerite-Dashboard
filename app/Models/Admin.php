<?php

namespace App\Models;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 *
 * @property-read \App\Models\Assembly|null $assembly
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Baptism> $baptisms
 * @property-read int|null $baptisms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesReceived
 * @property-read int|null $messages_received_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesSent
 * @property-read int|null $messages_sent_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Roles> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|User filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @mixin \Eloquent
 */
class Admin extends User implements ReportableModel, FilterableModel
{
    use Filterable;

    /**
     * Override the query for reporting to include only admin users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery()
    {
        // Use the existing User model's base query
        return User::whereHas('roles', function ($query) {
            $query->whereIn('roles.name', self::getAdminRoleNames());
        })->with('roles', 'assembly');
    }

    /**
     * Get the list of admin role names.
     *
     * @return array
     */
    public static function getAdminRoleNames()
    {
        return Roles::availableAdminsRoles()->toArray();
    }

    /**
     * Get the filterable attributes for this model, specifically for admin users.
     *
     * @return array
     */
    public static function getFilterableAttributes(): array
    {
        // Start with the User model's filterable attributes
        $attributes = parent::getFilterableAttributes();

        // Add admin-specific filterable attributes
        $adminAttributes = [
            'admin_role' => [
                'name' => 'admin_role',
                'display_name' => 'Rôle administratif',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains'],
                'custom_query' => true, // Flag to indicate this needs special handling
            ],
            'admin_since' => [
                'name' => 'admin_since',
                'display_name' => 'Administrateur depuis',
                'type' => 'date',
                'operators' => ['greater_than', 'less_than'],
                'custom_query' => true, // Flag to indicate this needs special handling
            ],
        ];

        // Merge the attributes
        return array_merge($attributes, $adminAttributes);
    }

    /**
     * Custom handling for dynamic filters specific to admin users.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public static function applyDynamicFilters(Builder $query, array $filters)
    {
        // Start with the parent model's dynamic filter application
        $query = parent::applyDynamicFilters($query, $filters);

        // Then apply Admin-specific filters
        foreach ($filters as $filter) {
            if (!isset($filter['field'], $filter['operator'], $filter['value'])) {
                continue;
            }

            // Handle admin_role filtering
            if ($filter['field'] === 'admin_role') {
                $query = self::applyAdminRoleFilter($query, $filter['operator'], $filter['value']);
            }

            // Handle admin_since filtering
            if ($filter['field'] === 'admin_since') {
                $query = self::applyAdminSinceFilter($query, $filter['operator'], $filter['value']);
            }
        }

        return $query;
    }

    /**
     * Apply filtering based on admin roles.
     *
     * @param Builder $query
     * @param string $operator
     * @param string $value
     * @return Builder
     */
    private static function applyAdminRoleFilter(Builder $query, string $operator, string $value): Builder
    {
        switch ($operator) {
            case 'equals':
                return $query->whereHas('roles', function ($q) use ($value) {
                    $q->where('name', $value)
                        ->orWhere('displayName', $value);
                });
            case 'not_equals':
                return $query->whereDoesntHave('roles', function ($q) use ($value) {
                    $q->where('name', $value)
                        ->orWhere('displayName', $value);
                });
            case 'contains':
                return $query->whereHas('roles', function ($q) use ($value) {
                    $q->where('name', 'like', '%' . $value . '%')
                        ->orWhere('displayName', 'like', '%' . $value . '%');
                });
            default:
                return $query;
        }
    }

    /**
     * Apply filtering based on when the user became an admin.
     *
     * @param Builder $query
     * @param string $operator
     * @param string $value
     * @return Builder
     */
    private static function applyAdminSinceFilter(Builder $query, string $operator, string $value): Builder
    {
        // Convert the value to a date
        $date = date('Y-m-d', strtotime($value));

        // We need to filter based on the earliest role assignment date
        // This is a complex query that involves the pivot table
        switch ($operator) {
            case 'greater_than':
                return $query->whereHas('roles', function ($q) use ($date) {
                    $q->whereIn('roles.name', self::getAdminRoleNames())
                        ->whereDate('role_user.created_at', '>', $date);
                });
            case 'less_than':
                return $query->whereHas('roles', function ($q) use ($date) {
                    $q->whereIn('roles.name', self::getAdminRoleNames())
                        ->whereDate('role_user.created_at', '<', $date);
                });
            default:
                return $query;
        }
    }

    /**
     * Get reportable columns for admin users.
     *
     * @return array
     */
    public static function getReportableColumns()
    {
        // Start with the parent User columns
        $columns = parent::getReportableColumns();

        // Add additional admin-specific columns
        $adminSpecificColumns = [
            'admin_roles' => [
                'title' => 'Rôles administratifs',
                'data' => function ($user) {
                    // Filter and display only admin roles
                    $adminRoles = $user->roles->filter(function ($role) {
                        return in_array($role->name, self::getAdminRoleNames());
                    });

                    return $adminRoles->pluck('displayName')->implode(', ');
                },
            ],
            'admin_since' => [
                'title' => 'Administrateur depuis',
                'data' => function ($user) {
                    // Find the earliest admin role assignment date
                    $earliestAdminRole = $user->roles()
                        ->whereIn('roles.name', self::getAdminRoleNames())
                        ->orderBy('role_user.created_at')
                        ->first();

                    return $earliestAdminRole && $earliestAdminRole->pivot && $earliestAdminRole->pivot->created_at
                        ? $earliestAdminRole->pivot->created_at->format('d/m/Y H:i')
                        : 'Non défini';
                },
            ],
        ];

        // Merge and reorder columns
        $mergedColumns = array_merge(
            array_slice($columns, 0, 4),  // Keep initial columns
            $adminSpecificColumns,         // Insert admin-specific columns
            array_slice($columns, 4)       // Add remaining original columns
        );

        return $mergedColumns;
    }

    /**
     * Get the report title.
     *
     * @return string
     */
    public static function getReportTitle()
    {
        return "Liste des administrateurs";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'name';
    }
}

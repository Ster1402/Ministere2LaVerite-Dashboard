<?php

namespace App\Models;

class Admin extends User
{
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

<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;

// php artisan db:seed --class=Database\Seeders\RolesSeeder
class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database with roles.
     *
     * @return void
     */
    public function run()
    {
        \DB::beginTransaction();

        if (Roles::where('name', '=', Roles::$SUDO)->doesntExist()) {
            Roles::create([
                'name' => Roles::$SUDO,
                'displayName' => 'SU ADMIN',
                'description' => 'Super administrateur de la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$ADMIN)->doesntExist()) {
            Roles::create([
                'name' => Roles::$ADMIN,
                'displayName' => 'Admin',
                'description' => 'Administrateur de la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$END_USER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$END_USER,
                'displayName' => 'Utilisateur final',
                'description' => 'Utilisateur de la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$USER_MANAGER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$USER_MANAGER,
                'displayName' => 'Gestionnaire d\'utilisateurs',
                'description' => 'Gérant des utilisateurs de la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$RESOURCE_MANAGER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$RESOURCE_MANAGER,
                'displayName' => 'Gestionnaire des ressources',
                'description' => 'Gérant des ressources dans la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$EVENT_MANAGER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$EVENT_MANAGER,
                'displayName' => 'Gestionnaire des ressources',
                'description' => 'Gérant des ressources dans la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$ASSEMBLY_MANAGER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$ASSEMBLY_MANAGER,
                'displayName' => 'Gestionnaire des assemblées',
                'description' => 'Gérant des assemblées dans la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$MEDIA_MANAGER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$MEDIA_MANAGER,
                'displayName' => 'Gestionnaire des médias',
                'description' => 'Gérant des médias dans la plateforme',
            ]);
        }

        if (Roles::where('name', '=', Roles::$HUB_MANAGER)->doesntExist()) {
            Roles::create([
                'name' => Roles::$HUB_MANAGER,
                'displayName' => 'Gestionnaire des HUB',
                'description' => 'Gérant des HUB dans la plateforme',
            ]);
        }

        \DB::commit();
    }
}

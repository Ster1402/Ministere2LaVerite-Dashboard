php artisan migrate:refresh
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=SudoAdminsSeeder ## Change the Sudo admin information in the Seeder class

## Only for testing
php artisan db:seed --class=TestDatabaseSeeder

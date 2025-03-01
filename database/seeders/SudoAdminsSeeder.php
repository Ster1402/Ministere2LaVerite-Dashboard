<?php


namespace Database\Seeders;


use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// php artisan db:seed --class=Database\Seeders\SudoAdminsSeeder
class SudoAdminsSeeder extends Seeder
{
    /**
     * Seed the application's database with roles.
     *
     * @return void
     */
    public function run()
    {
        \DB::beginTransaction();

        $sudoRole = Roles::firstWhere('name', '=', Roles::$SUDO);

        User::firstWhere('email', '=', 'sterdevs@gmail.com')?->delete();

        $sudo = User::create([
            'name' => 'M. Honore',
            'surname' => 'Bertran',
            'gender' => 'male',
            'email' => env('SUDO_ADMIN_1_USER', 'honorebertran008@yahoo.fr'),
            'password' => Hash::make(env('SUDO_ADMIN_1_PASSWORD', "SN9percr3W##2002AS")),
        ]);

        $sudo->roles()->attach($sudoRole);

        $sudo1 = User::create([
            'name' => 'M. Mbouny',
            'gender' => 'male',
            'email' => env('SUDO_ADMIN_2_USER', 'Mbouny2018@gmail.com'),
            'password' => Hash::make(env('SUDO_ADMIN_2_PASSWORD', "Jesus@@2024")),
        ]);

        $sudo1->roles()->attach($sudoRole);

        \DB::commit();
    }
}

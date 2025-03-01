<?php

namespace Database\Seeders;

use App\Models\Assembly;
use App\Models\AssemblyEvent;
use App\Models\AssemblyMessage;
use App\Models\Baptism;
use App\Models\Borrowed;
use App\Models\Media;
use App\Models\Event;
use App\Models\Group;
use App\Models\Message;
use App\Models\Resource;
use App\Models\Sector;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::beginTransaction();

        User::factory(30)->create();

        Sector::factory(5)->create();
        Assembly::factory(10)->create();

        Message::factory(20)->create();

        AssemblyMessage::factory(15)->create();

        $sudo_admin = User::firstWhere('email', '=', 'sterdevs@gmail.com');

        Message::factory(20)->create([
            'receiverId' => $sudo_admin
        ]);

        Baptism::factory(10)->create();

        Media::factory(10)->create();
        Event::factory(12)->create();

        AssemblyEvent::factory(45)->create();

        Group::factory(10)->create();

        Resource::factory(10)->create();
        Borrowed::factory(4)->create();

        Transaction::factory(15)->create();

        \DB::commit();
    }

}

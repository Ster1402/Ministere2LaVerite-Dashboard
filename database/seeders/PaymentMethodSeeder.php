<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Orange Money
        PaymentMethod::create([
            'name' => 'orange_money',
            'display_name' => 'Orange Money',
            'description' => 'Paiement via Orange Money Cameroun',
            'phone_regex' => '^(69[0-9]{7}|65[5-9][0-9]{6}|68[5-9][0-9]{6})$',
            'phone_prefix' => '69, 655-659, 685-689',
            'color_code' => '#FF6600',
            'order' => 1
        ]);

        // MTN Mobile Money
        PaymentMethod::create([
            'name' => 'mtn_momo',
            'display_name' => 'MTN Mobile Money',
            'description' => 'Paiement via MTN Mobile Money Cameroun',
            'phone_regex' => '^(67[0-9]{7}|65[0-4][0-9]{6}|68[0-4][0-9]{6})$',
            'phone_prefix' => '67, 650-654, 680-684',
            'color_code' => '#FFCC00',
            'order' => 2
        ]);
    }
}

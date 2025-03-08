<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donor_name')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('XAF');
            $table->string('payment_method');
            $table->string('phone_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('reference')->unique();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'canceled'])->default('pending');
            $table->json('payment_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};

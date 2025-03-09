<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Add fields only if they don't exist
            if (!Schema::hasColumn('donations', 'donation_date')) {
                $table->timestamp('donation_date')->nullable();
            }
            if (!Schema::hasColumn('donations', 'payment_data')) {
                $table->json('payment_data')->nullable();
            }
            if (!Schema::hasColumn('donations', 'is_pending')) {
                $table->boolean('is_pending')->default(false);
            }
            if (!Schema::hasColumn('donations', 'is_completed')) {
                $table->boolean('is_completed')->default(false);
            }
            if (!Schema::hasColumn('donations', 'is_failed')) {
                $table->boolean('is_failed')->default(false);
            }
            if (!Schema::hasColumn('donations', 'is_anonymous')) {
                $table->boolean('is_anonymous')->default(false);
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            //
        });
    }
};

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
        // First check if we have the old donor fields from the previous implementation
        if (Schema::hasColumn('transactions', 'donation_type')) {
            // Remove the old donation_type column if it exists
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('donation_type');
            });
        }

        // Add payment_method_id column if it doesn't exist
        if (!Schema::hasColumn('transactions', 'payment_method_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('payment_method_id')->nullable()->after('donor_phone')
                    ->constrained()->nullOnDelete();
            });
        }

        // Make sure all other fields exist as well
        if (!Schema::hasColumn('transactions', 'donor_name')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('donor_name')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('transactions', 'donor_email')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('donor_email')->nullable()->after('donor_name');
            });
        }

        if (!Schema::hasColumn('transactions', 'donor_phone')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('donor_phone')->nullable()->after('donor_email');
            });
        }

        if (!Schema::hasColumn('transactions', 'is_processed')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->boolean('is_processed')->default(false)->after('payment_method_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove the new columns
            if (Schema::hasColumn('transactions', 'payment_method_id')) {
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            }

            // Add back the old donation_type column
            if (!Schema::hasColumn('transactions', 'donation_type')) {
                $table->string('donation_type')->nullable()->after('donor_phone');
            }
        });
    }
};

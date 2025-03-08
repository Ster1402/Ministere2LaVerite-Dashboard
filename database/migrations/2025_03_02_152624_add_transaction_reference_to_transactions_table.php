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
        Schema::table('transactions', function (Blueprint $table) {
            // Add transaction_reference column if it doesn't exist
            if (!Schema::hasColumn('transactions', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('is_processed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'transaction_reference')) {
                $table->dropColumn('transaction_reference');
            }
        });
    }
};

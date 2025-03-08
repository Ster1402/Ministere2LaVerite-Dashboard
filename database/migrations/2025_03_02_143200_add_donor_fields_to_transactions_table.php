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
            $table->string('donor_name')->nullable()->after('user_id');
            $table->string('donor_email')->nullable()->after('donor_name');
            $table->string('donor_phone')->nullable()->after('donor_email');
            $table->string('donation_type')->nullable()->after('donor_phone');
            $table->boolean('is_processed')->default(false)->after('donation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'donor_name',
                'donor_email',
                'donor_phone',
                'donation_type',
                'is_processed'
            ]);
        });
    }
};


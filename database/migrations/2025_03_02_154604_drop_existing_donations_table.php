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
        Schema::dropIfExists('donations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Since we're dropping the table, we can't easily recreate it
        // with the exact same structure in the down() method.
        // You should have a backup of the data if you need to restore it.
    }
};

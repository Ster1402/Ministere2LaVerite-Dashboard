<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('baptisms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->references('id')->on('users')->cascadeOnDelete();
            $table->string('type')->comment('Values: none, water, holy-spirit, both-water-and-holy-spirit ')
                ->default('none')
                ->nullable();
            $table->string('nominalMaker')->nullable();
            $table->boolean('hasHolySpirit')->default(false);
            $table->string('ministerialLevel')->nullable();
            $table->integer('spiritualLevel')->default(0);
            $table->timestamp('date_water')->nullable();
            $table->timestamp('date_holy_spirit')->nullable();
            $table->timestamp('date_latest')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baptisms');
    }
};

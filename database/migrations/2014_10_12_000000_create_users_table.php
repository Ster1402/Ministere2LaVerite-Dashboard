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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('profession')->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('dateOfBirth')->nullable();
            $table->string('residence')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('antecedent')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->boolean('isActive')->default(true);
            $table->boolean('isDisciplined')->default(true);
            $table->timestamp('arrivalDate')->nullable();
            $table->string('maritalStatus')->nullable();
            $table->integer('numberOfChildren')->default(0);
            $table->boolean('sterileWoman')->default(false);
            $table->text('seriousIllnesses')->nullable();
            $table->text('comment')->nullable();
            // Assemblies
            $table->foreignId('assembly_id')->nullable();
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

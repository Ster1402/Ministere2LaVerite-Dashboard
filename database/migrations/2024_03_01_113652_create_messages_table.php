<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('senderId')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('receiverId')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('message_id')->comment("The id of the message we're replying to.")
                ->nullable()->references('id')->on('messages')->cascadeOnDelete();
            $table->string('category')->nullable();
            $table->string('picture_path')->nullable();
            $table->string('tags')->nullable();
            $table->boolean('received')->default(true);
            $table->boolean('deleted')->default(false);
            $table->boolean('seen')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

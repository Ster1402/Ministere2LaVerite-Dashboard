<?php

use App\Models\Assembly;
use App\Models\Media;
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
        Schema::create('assembly_media', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Assembly::class)->references('id')->on('assemblies')->cascadeOnDelete();
            $table->foreignIdFor(Media::class)->references('id')->on('medias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assembly_media');
    }
};

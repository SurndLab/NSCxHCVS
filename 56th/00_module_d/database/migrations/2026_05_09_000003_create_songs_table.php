<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('albums')->cascadeOnDelete();
            $table->string('title');
            $table->integer('duration_seconds');
            $table->text('lyrics')->nullable();
            $table->integer('order')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['album_id', 'order']);
            $table->index(['view_count']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};

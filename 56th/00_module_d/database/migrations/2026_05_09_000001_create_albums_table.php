<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('artist');
            $table->smallInteger('release_year');
            $table->string('genre')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['title']);
            $table->index(['release_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};

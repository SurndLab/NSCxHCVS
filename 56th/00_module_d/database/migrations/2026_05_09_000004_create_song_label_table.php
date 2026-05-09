<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('song_label', function (Blueprint $table) {
            $table->foreignId('song_id')->constrained('songs')->cascadeOnDelete();
            $table->foreignId('label_id')->constrained('labels')->cascadeOnDelete();
            $table->primary(['song_id', 'label_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('song_label');
    }
};

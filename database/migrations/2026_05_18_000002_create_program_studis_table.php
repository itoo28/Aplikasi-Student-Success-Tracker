<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->constrained('fakultas')->cascadeOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenjang', ['S1', 'D4', 'D3']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['fakultas_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};

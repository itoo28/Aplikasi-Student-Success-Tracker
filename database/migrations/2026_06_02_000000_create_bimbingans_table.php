<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('semester')->nullable();
            $table->string('topik');
            $table->text('catatan')->nullable();
            $table->enum('tipe_pengajuan', ['mandiri_mahasiswa', 'undangan_dosen'])->default('mandiri_mahasiswa');
            $table->enum('status', ['pending', 'validated', 'revised', 'completed'])->default('pending');
            $table->string('document_path')->nullable();
            $table->text('resolution')->nullable();
            $table->string('activity_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};

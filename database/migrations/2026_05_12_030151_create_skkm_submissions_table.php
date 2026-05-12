<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skkm_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('point_rule_id')->constrained('point_rules')->cascadeOnDelete();
            $table->string('nama_kegiatan', 255);
            $table->string('penyelenggara', 255);
            $table->date('tanggal_kegiatan');
            $table->string('file_bukti', 500);
            $table->tinyInteger('semester_input');
            $table->integer('poin_otomatis');
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skkm_submissions');
    }
};

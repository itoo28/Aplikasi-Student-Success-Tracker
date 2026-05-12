<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skkm_progress', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete()->primary();
            $table->enum('jenjang', ['S1', 'D4', 'D3']);
            $table->tinyInteger('semester_aktif')->default(1);
            $table->integer('poin_smt_1_2')->default(0);
            $table->integer('poin_smt_3_4')->default(0);
            $table->integer('poin_smt_5_6')->default(0);
            $table->integer('poin_smt_7_8')->default(0);
            $table->integer('total_poin')->default(0);
            $table->enum('status_yudisium', ['memenuhi', 'belum_memenuhi', 'dalam_proses'])->default('dalam_proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skkm_progress');
    }
};

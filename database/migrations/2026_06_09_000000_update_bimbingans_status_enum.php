<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->enum('status', ['pending', 'validated', 'revised', 'completed', 'canceled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->enum('status', ['pending', 'validated', 'revised', 'completed'])->default('pending')->change();
        });
    }
};

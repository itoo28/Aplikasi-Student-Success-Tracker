<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'skkm_role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('skkm_role', ['mahasiswa', 'student', 'dosen_pa', 'kaprodi', 'kemahasiswaan', 'super_admin'])
                    ->default('mahasiswa')
                    ->after('role');
            });
        }

        if (! Schema::hasColumn('users', 'program_studi_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('program_studi_id')->nullable()->after('lecturer_id')->constrained('program_studis')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('users', 'jenjang_studi')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('jenjang_studi', ['S1', 'D4', 'D3'])->nullable()->after('semester');
            });
        }

        if (! Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('password');
            });
        }

        if (Schema::hasColumn('users', 'skkm_role')) {
            DB::table('users')
                ->where('role', 'lecturer')
                ->where(function ($query) {
                    $query->whereNull('skkm_role')->orWhereIn('skkm_role', ['student', 'mahasiswa']);
                })
                ->update(['skkm_role' => 'dosen_pa']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'program_studi_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('program_studi_id');
            });
        }

        $columnsToDrop = [];
        foreach (['skkm_role', 'jenjang_studi', 'is_active'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if ($columnsToDrop !== []) {
            Schema::table('users', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};

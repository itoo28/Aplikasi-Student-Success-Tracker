<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('skkm_submissions', 'status_kaprodi')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->enum('status_kaprodi', ['pending', 'disetujui', 'ditolak'])->nullable()->after('status_verifikasi');
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'catatan_kaprodi')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->text('catatan_kaprodi')->nullable()->after('catatan_dosen');
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'kaprodi_verified_by')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->foreignId('kaprodi_verified_by')->nullable()->after('verified_by')->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'kaprodi_verified_at')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->timestamp('kaprodi_verified_at')->nullable()->after('verified_at');
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'status_kemahasiswaan')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->enum('status_kemahasiswaan', ['pending', 'disetujui', 'ditolak'])->nullable()->after('status_kaprodi');
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'catatan_kemahasiswaan')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->text('catatan_kemahasiswaan')->nullable()->after('catatan_kaprodi');
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'kemahasiswaan_verified_by')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->foreignId('kemahasiswaan_verified_by')->nullable()->after('kaprodi_verified_by')->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'kemahasiswaan_verified_at')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->timestamp('kemahasiswaan_verified_at')->nullable()->after('kaprodi_verified_at');
            });
        }

        if (! Schema::hasColumn('skkm_submissions', 'is_progress_counted')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->boolean('is_progress_counted')->default(false)->after('kemahasiswaan_verified_at');
            });
        }

        if (Schema::hasColumn('skkm_submissions', 'is_progress_counted')) {
            DB::table('skkm_submissions')
                ->where('status_verifikasi', 'disetujui')
                ->where('is_progress_counted', false)
                ->update(['is_progress_counted' => true]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('skkm_submissions', 'kaprodi_verified_by')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('kaprodi_verified_by');
            });
        }

        if (Schema::hasColumn('skkm_submissions', 'kemahasiswaan_verified_by')) {
            Schema::table('skkm_submissions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('kemahasiswaan_verified_by');
            });
        }

        $columnsToDrop = [];
        foreach ([
            'status_kaprodi',
            'status_kemahasiswaan',
            'catatan_kaprodi',
            'catatan_kemahasiswaan',
            'kaprodi_verified_at',
            'kemahasiswaan_verified_at',
            'is_progress_counted',
        ] as $column) {
            if (Schema::hasColumn('skkm_submissions', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if ($columnsToDrop !== []) {
            Schema::table('skkm_submissions', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};

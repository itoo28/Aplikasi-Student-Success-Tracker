<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql'
            || ! Schema::hasTable('point_rules')
            || ! Schema::hasColumn('point_rules', 'tingkat')) {
            return;
        }

        DB::statement(
            "ALTER TABLE `point_rules` MODIFY `tingkat` ENUM('internasional','nasional','regional','provinsi','universitas','fakultas','lokal') NULL"
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql'
            || ! Schema::hasTable('point_rules')
            || ! Schema::hasColumn('point_rules', 'tingkat')) {
            return;
        }

        DB::table('point_rules')
            ->where('tingkat', 'provinsi')
            ->update(['tingkat' => 'regional']);

        DB::statement(
            "ALTER TABLE `point_rules` MODIFY `tingkat` ENUM('internasional','nasional','regional','universitas','fakultas','lokal') NULL"
        );
    }
};

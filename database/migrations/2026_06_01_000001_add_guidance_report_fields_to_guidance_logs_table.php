<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guidance_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('guidance_logs', 'activity_photo_path')) {
                $table->string('activity_photo_path')->nullable()->after('document_path');
            }

            if (! Schema::hasColumn('guidance_logs', 'resolution')) {
                $table->text('resolution')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('guidance_logs', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('resolution');
            }
        });
    }

    public function down(): void
    {
        Schema::table('guidance_logs', function (Blueprint $table) {
            if (Schema::hasColumn('guidance_logs', 'activity_photo_path')) {
                $table->dropColumn('activity_photo_path');
            }

            if (Schema::hasColumn('guidance_logs', 'resolution')) {
                $table->dropColumn('resolution');
            }

            if (Schema::hasColumn('guidance_logs', 'is_completed')) {
                $table->dropColumn('is_completed');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('id');
            $table->string('group_type')->nullable()->after('group_id');
            $table->string('group_value')->nullable()->after('group_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn(['group_id', 'group_type', 'group_value']);
        });
    }
};

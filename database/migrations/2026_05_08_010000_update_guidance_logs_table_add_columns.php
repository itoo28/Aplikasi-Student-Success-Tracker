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
        Schema::table('guidance_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->after('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('guidance_date')->after('lecturer_id');
            $table->string('topic')->after('guidance_date');
            $table->text('notes')->nullable()->after('topic');
            $table->enum('status', ['pending', 'validated', 'revised'])->default('pending')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guidance_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lecturer_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['guidance_date', 'topic', 'notes', 'status']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('mode', ['online','offline','hybrid'])->default('offline')->after('price');
            // opsional: untuk online meeting umum (kalau perlu di level course)
            $table->string('default_meeting_url')->nullable()->after('mode');
            $table->string('default_meeting_platform')->nullable()->after('default_meeting_url'); // zoom/google meet/youtube
        });
    }
    public function down(): void {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['mode','default_meeting_url','default_meeting_platform']);
        });
    }
};
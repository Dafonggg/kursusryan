<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->enum('modality', ['online', 'offline'])->default('offline')->after('course_id');
                $table->string('online_access_url')->nullable()->after('modality'); // contoh: link YouTube/portal
                $table->timestamp('module_kit_issued_at')->nullable()->after('online_access_url'); // untuk offline: tanggal modul fisik diberikan
            });
        }
        public function down(): void {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropColumn(['modality','online_access_url','module_kit_issued_at']);
            });
        }
    };
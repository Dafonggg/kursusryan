<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->enum('scope', ['online','offline','both'])->default('both')->after('type');
        });
    }
    public function down(): void {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->dropColumn('scope');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();

            $table->string('certificate_no')->unique();  // contoh: RK-2025-000123
            $table->timestamp('issued_at');
            $table->string('file_path')->nullable();     // storage path pdf/png
            $table->string('verification_hash', 64)->nullable()->unique(); // sha256 utk verifikasi
            $table->json('meta')->nullable();            // nilai akhir, dll

            $table->timestamps();

            $table->unique('enrollment_id'); // 1 enrollment = 1 sertifikat
        });
    }

    public function down(): void {
        Schema::dropIfExists('certificates');
    }
};

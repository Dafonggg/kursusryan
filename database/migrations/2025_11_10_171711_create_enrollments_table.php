<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // student
            $table->foreignId('course_id')->constrained()->cascadeOnDelete(); // kursus
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['pending','active','completed','expired','cancelled'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id','course_id']); // satu peserta per kursus
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

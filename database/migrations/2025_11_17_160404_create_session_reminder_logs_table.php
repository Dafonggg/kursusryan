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
        Schema::create('session_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_session_id')->constrained('course_sessions')->cascadeOnDelete();
            $table->timestamp('sent_at');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->timestamps();

            $table->unique('course_session_id'); // Satu log per session
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_reminder_logs');
    }
};

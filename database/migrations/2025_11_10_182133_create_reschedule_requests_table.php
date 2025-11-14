<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_session_id')->constrained()->cascadeOnDelete();

            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('proposed_at');               // waktu pengganti yang diajukan
            $table->text('reason')->nullable();

            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->text('decision_notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'proposed_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('reschedule_requests');
    }
};

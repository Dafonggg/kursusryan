<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('course_image')->nullable();
            $table->enum('mode', ['online','offline','hybrid'])->nullable(); // override dari course kalau perlu
            $table->timestamp('scheduled_at');
            $table->unsignedSmallInteger('duration_minutes')->default(90);

            // khusus online/offline:
            $table->string('meeting_url')->nullable();     // untuk online (zoom/meet/yt)
            $table->string('meeting_platform')->nullable();
            $table->string('location')->nullable();        // untuk offline (alamat/ruangan)
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_sessions');
    }
};

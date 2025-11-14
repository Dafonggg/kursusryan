<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedTinyInteger('duration_months')->default(3);
            $table->unsignedInteger('price')->default(0);
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            // course bisa mendukung dua-duanya; peserta pilih di enrollment
            $table->boolean('supports_online')->default(true);
            $table->boolean('supports_offline')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('courses');
    }
};

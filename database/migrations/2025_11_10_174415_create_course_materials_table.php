<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // video|document
            $table->string('title');
            $table->string('path')->nullable(); // file upload
            $table->string('url')->nullable();  // link (YouTube, Drive, dll.)
            $table->boolean('online_only')->default(false);   // tampilkan hanya utk enrollment online
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};

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
        Schema::create('materins', function (Blueprint $table) {
            $table->id('id_materin');
            $table->unsignedBigInteger('id_kursus');
            $table->foreign('id_kursus')
                ->references('id_kursus')
                ->on('kursus')
                ->onDelete('cascade');

            $table->enum('jenis_file', ['pdf', 'doc', 'ppt', 'video', 'link']);
            $table->string('file_materin')->nullable();
            $table->string('link_video')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materins');
    }
};

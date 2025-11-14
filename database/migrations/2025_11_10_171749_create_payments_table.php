<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->enum('method', ['cash','transfer','qris','gateway'])->default('cash');
            $table->enum('status', ['pending','paid','failed','refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('reference')->nullable(); // no. invoice / trx id
            $table->json('meta')->nullable();        // bukti, bank, dll
            $table->timestamps();

            $table->index(['status','method']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

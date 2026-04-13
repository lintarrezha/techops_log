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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_type_id')->constrained();
            $table->timestamp('tanggal_kegiatan');
            $table->enum('status_kegiatan', ['normal', 'ada_kendala', 'kritis'])->default('normal');
            $table->text('catatan')->nullable();
            $table->json('custom_sections')->nullable(); // seksi tambahan dari user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

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
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_type_id')->constrained()->cascadeOnDelete();
            $table->string('section_label');           // "A", "B", "C" dst
            $table->string('section_name');            // "Status Umum Server"
            $table->string('pertanyaan');
            $table->enum('tipe_input', ['text', 'number', 'radio', 'textarea', 'select']);
            $table->json('opsi_jawaban')->nullable();  // untuk tipe radio/select
            $table->string('satuan')->nullable();      // "%" atau "GB" untuk number
            $table->boolean('is_required')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_templates');
    }
};

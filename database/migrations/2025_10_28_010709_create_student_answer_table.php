<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_answer', function (Blueprint $table) {
            $table->increments('jawaban_id'); // Tetap jawaban_id
            $table->unsignedInteger('hasil_id'); // Tetap hasil_id

            // --- PERBAIKAN DI SINI ---
            // Sebelumnya: soal_id (integer) -> ke tabel soal
            // Sekarang: question_id (bigInteger) -> ke tabel questions
            $table->foreignId('question_id')
                ->constrained('question') // Arahkan ke tabel 'questions'
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            // -------------------------

            $table->text('jawaban_siswa')->nullable();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_auto_save')->nullable();

            $table->foreign('hasil_id')
                ->references('hasil_id')
                ->on('exam_result')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answer');
    }
};

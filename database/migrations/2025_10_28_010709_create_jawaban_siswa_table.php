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
        Schema::create('student_answer', function (Blueprint $table) {
            $table->increments('jawaban_id');
            $table->unsignedInteger('hasil_id');
            $table->unsignedInteger('soal_id');
            $table->text('jawaban_siswa')->nullable();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_auto_save')->nullable();
            $table->foreign('hasil_id')
                ->references('hasil_id')
                ->on('exam_result')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('soal_id')
                ->references('soal_id')
                ->on('soal')
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

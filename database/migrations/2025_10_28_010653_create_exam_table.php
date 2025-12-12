<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam', function (Blueprint $table) {
            $table->increments('ujian_id'); // TETAP ujian_id
            $table->string('nama_ujian', 100);

            // --- BAGIAN INI SAYA TAMBAHKAN ---
            // Supaya ujian tau pakai paket soal yg mana
            $table->foreignId('question_set_id')
                ->nullable()
                ->constrained('question_set') // Nyambung ke tabel question_sets
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            // ---------------------------------

            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->unsignedSmallInteger('durasi')->comment('Durasi ujian dalam menit');

            $table->unsignedInteger('guru_id'); // Tetap guru_id
            $table->unsignedInteger('admin_id')->nullable(); // Tetap admin_id

            $table->foreign('guru_id')
                ->references('guru_id')
                ->on('teacher')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Hati-hati kalau tabel admin belum ada, baris di bawah bisa error
            // $table->foreign('admin_id')->references('admin_id')->on('admin')...;

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam');
    }
};

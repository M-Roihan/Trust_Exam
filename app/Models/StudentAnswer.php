<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'student_answer';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'hasil_id',       // Foreign key ke tabel exam_result
        'question_id',    // Foreign key ke tabel question
        'jawaban_siswa',  // Index jawaban (0, 1, 2, 3, dst)
        'waktu_auto_save' // Mencatat kapan jawaban disimpan
    ];

    protected $casts = [
        'waktu_auto_save' => 'datetime',
    ];

    // --- RELASI (Opsional tapi bagus untuk kerapihan) ---

    // Jawaban ini milik Hasil Ujian siapa?
    public function examResult()
    {
        return $this->belongsTo(ExamResult::class, 'hasil_id');
    }

    // Jawaban ini untuk Soal nomor berapa?
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
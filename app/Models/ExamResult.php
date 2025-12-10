<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    // Nama tabel di database (sesuai migrasi yang kita buat)
    protected $table = 'exam_result';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'exam_id',
        'student_id',
        'nilai',
        'status', // 'Belum Mulai', 'Sedang Dikerjakan', 'Selesai'
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relasi ke Ujian
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Relasi ke Siswa
    public function student()
    {
        // Sesuaikan 'student_id' dengan kolom di tabel siswa kamu jika perlu
        return $this->belongsTo(Siswa::class, 'student_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $table = 'exam_result';

    // --- INI PERBAIKANNYA ---
    // Kita wajib memberi tahu Laravel nama primary key-nya
    protected $primaryKey = 'hasil_id'; 
    // -------------------------

    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'nilai',
        'status', 
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'ujian_id');
    }

    public function student()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
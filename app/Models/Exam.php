<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // Nama tabel singular (sesuai migrasi kamu)
    protected $table = 'exam';

    // Primary Key (sesuai migrasi kamu)
    protected $primaryKey = 'ujian_id';

    protected $guarded = ['ujian_id'];

    // Relasi ke Paket Soal
    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class, 'question_set_id');
    }

    // Relasi ke Guru
    public function teacher()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
    }
}

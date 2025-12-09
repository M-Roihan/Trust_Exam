<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    use HasFactory;

    // --- TAMBAHAN PENTING: Mendefinisikan nama tabel singular ---
    protected $table = 'question_set';
    // ------------------------------------------------------------

    protected $fillable = [
        'teacher_id',
        'subject',
        'exam_type',
        'semester',
        'class_level',
        'description',
    ];

    public function teacher()
    {
        // Pastikan model Guru sudah benar (sesuai file Guru.php kamu)
        return $this->belongsTo(Guru::class, 'teacher_id', 'guru_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
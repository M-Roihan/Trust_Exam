<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\QuestionSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherExamController extends Controller
{
    /**
     * Menampilkan daftar jadwal ujian milik guru ini.
     */
    public function index(): View|RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        // Ambil data ujian urut dari yang terbaru
        $exams = Exam::with('questionSet')
            ->where('guru_id', $teacher['id'])
            ->orderByDesc('created_at')
            ->get();

        return view('guru.exams.index', compact('teacher', 'exams'));
    }

    /**
     * Form membuat jadwal ujian baru.
     */
    public function create(): View|RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        // Ambil daftar Paket Soal milik guru ini untuk dropdown
        $questionSets = QuestionSet::withCount('questions')
            ->where('teacher_id', $teacher['id'])
            ->orderByDesc('updated_at')
            ->get();

        return view('guru.exams.create', compact('teacher', 'questionSets'));
    }

    /**
     * Simpan jadwal ujian ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        $validated = $request->validate([
            'nama_ujian' => 'required|string|max:100',
            'question_set_id' => 'required|exists:question_set,id', // Pastikan paket soal ada
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai', // Selesai harus setelah Mulai
            'durasi' => 'required|integer|min:1', // Dalam menit
        ]);

        Exam::create([
            'guru_id' => $teacher['id'],
            'nama_ujian' => $validated['nama_ujian'],
            'question_set_id' => $validated['question_set_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'durasi' => $validated['durasi'],
            // admin_id biarkan null
        ]);

        return redirect()->route('teacher.exams.index')->with('status', 'Jadwal ujian berhasil dibuat.');
    }

    /**
     * Hapus jadwal ujian.
     */
    public function destroy($id): RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login');
        }

        $exam = Exam::where('ujian_id', $id)->where('guru_id', $teacher['id'])->firstOrFail();
        $exam->delete();

        return redirect()->route('teacher.exams.index')->with('status', 'Jadwal ujian dihapus.');
    }

    // Helper session guru
    private function resolveTeacher(): ?array
    {
        $teacher = session('teacher');

        return ($teacher && array_key_exists('id', $teacher)) ? $teacher : null;
    }
}

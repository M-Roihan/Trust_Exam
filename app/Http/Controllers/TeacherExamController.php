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

    /**
     * Ekspor Rekap Hasil Ujian ke format CSV (Excel)
     */
    public function exportResult()
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login');
        }

        $results = \App\Models\ExamResult::with(['student', 'exam.questionSet'])
            ->whereHas('exam', function ($query) use ($teacher) {
                $query->where('guru_id', $teacher['id']);
            })
            ->latest()
            ->get();

        $fileName = 'Rekap_Hasil_Ujian_'.date('d-m-Y').'.csv';

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($results) {
            $file = fopen('php://output', 'w');

            // 1. Tambahkan BOM agar Excel mengenali format UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // 2. Beritahu Excel secara paksa untuk menggunakan titik koma (sep=;)
            fwrite($file, "sep=;\n");

            // 3. Header kolom menggunakan pemisah titik koma
            fputcsv($file, ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Nama Ujian', 'Mata Pelajaran', 'Tanggal', 'Nilai'], ';');

            foreach ($results as $key => $row) {
                // 4. Data menggunakan pemisah titik koma
                fputcsv($file, [
                    $key + 1,
                    $row->student->nis ?? '-',
                    $row->student->nama_siswa ?? '-',
                    $row->student->kelas ?? '-',
                    $row->exam->nama_ujian ?? '-',
                    $row->exam->questionSet->subject ?? '-',
                    $row->updated_at->format('d/m/Y H:i'),
                    $row->nilai,
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

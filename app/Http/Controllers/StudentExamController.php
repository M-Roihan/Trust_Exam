<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\StudentAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    /**
     * 1. Halaman Konfirmasi ("Apakah Anda Yakin?")
     */
    public function confirmation($examId)
    {
        $student = session('student');
        if (! $student) {
            return redirect()->route('login');
        }

        // Ambil data ujian lengkap dengan Guru & Paket Soal
        $exam = Exam::with(['questionSet', 'teacher'])->findOrFail($examId);

        // Cek apakah siswa sudah pernah mengerjakan dan STATUSNYA SELESAI
        $result = ExamResult::where('ujian_id', $examId)
            ->where('siswa_id', $student['id'])
            ->first();

        if ($result && $result->status == 'Selesai') {
            return redirect()->route('student.dashboard')->with('status', 'Anda sudah menyelesaikan ujian ini.');
        }

        return view('siswa.exam.confirmation', compact('exam', 'student'));
    }

    /**
     * 2. Logika Mulai Mengerjakan (Start Timer)
     */
    public function start($examId)
    {
        $student = session('student');

        // Cari data pengerjaan (resume) atau Buat baru (start)
        $examResult = ExamResult::firstOrCreate(
            [
                'ujian_id' => $examId,
                'siswa_id' => $student['id'],
            ],
            [
                'status' => 'Sedang Dikerjakan',
                'waktu_mulai' => Carbon::now(),
                // Waktu selesai dihitung di bawah
            ]
        );

        // PERBAIKAN 1: Auto-Repair saat Start
        // Cek jika data baru dibuat ATAU waktu_selesai masih kosong (akibat error sebelumnya)
        if ($examResult->wasRecentlyCreated || empty($examResult->waktu_selesai)) {
            $exam = Exam::find($examId);

            // Hitung jam selesai: Sekarang + Durasi Menit
            $examResult->waktu_selesai = Carbon::now()->addMinutes($exam->durasi);
            $examResult->save();
        }

        // Redirect ke Soal Nomor 1
        return redirect()->route('student.exam.show', ['exam' => $examId, 'number' => 1]);
    }

    /**
     * 3. Halaman Soal Utama (Tampil per satu soal)
     */
    public function show($examId, $number = 1)
    {
        $student = session('student');
        $exam = Exam::findOrFail($examId);

        // Ambil data pengerjaan siswa
        $examResult = ExamResult::where('ujian_id', $examId)
            ->where('siswa_id', $student['id'])
            ->firstOrFail();

        // PERBAIKAN 2: Auto-Repair saat Show (PENTING!)
        // Jika karena error sebelumnya waktu_selesai jadi NULL, kita perbaiki di sini
        if (is_null($examResult->waktu_selesai)) {
            $examResult->waktu_selesai = Carbon::now()->addMinutes($exam->durasi);
            $examResult->save();
            $examResult->refresh(); // Ambil data terbaru
        }

        // CEK 1: Apakah waktu sudah habis?
        if (Carbon::now()->greaterThan($examResult->waktu_selesai) && $examResult->status !== 'Selesai') {
            return $this->finish($examId); // Paksa selesai
        }

        // CEK 2: Apakah status sudah selesai?
        if ($examResult->status == 'Selesai') {
            return redirect()->route('student.dashboard');
        }

        // Ambil semua soal dari paket soal ini
        $questions = Question::where('question_set_id', $exam->question_set_id)
            ->orderBy('order') // Urutkan sesuai nomor
            ->get();

        // Logika Pagination Manual (Index Array mulai dari 0)
        $index = $number - 1;

        // Jika nomor soal tidak valid, kembalikan ke nomor 1
        if (! isset($questions[$index])) {
            return redirect()->route('student.exam.show', ['exam' => $examId, 'number' => 1]);
        }

        $currentQuestion = $questions[$index];

        // Cek apakah siswa sudah menjawab soal ini sebelumnya?
        $existingAnswer = StudentAnswer::where('hasil_id', $examResult->hasil_id) // Pakai hasil_id (PK ExamResult)
            ->where('question_id', $currentQuestion->id)
            ->first();

        // Hitung SISA WAKTU untuk Timer (dalam detik)
        $timeLeft = Carbon::now()->diffInSeconds($examResult->waktu_selesai, false);

        return view('siswa.exam.show', compact(
            'exam', 'questions', 'currentQuestion', 'number', 'existingAnswer', 'timeLeft', 'examResult'
        ));
    }

    /**
     * 4. Simpan Jawaban (Dipanggil via AJAX/JavaScript)
     */
    public function saveAnswer(Request $request)
    {
        // Validasi input dari JavaScript
        $request->validate([
            'exam_result_id' => 'required',
            'question_id' => 'required',
            'answer_index' => 'required', // 0=A, 1=B, dst
        ]);

        // Simpan atau Update Jawaban di tabel student_answer
        StudentAnswer::updateOrCreate(
            [
                'hasil_id' => $request->exam_result_id,
                'question_id' => $request->question_id,
            ],
            [
                'jawaban_siswa' => $request->answer_index,
                'waktu_auto_save' => Carbon::now(),
            ]
        );

        return response()->json(['status' => 'success']);
    }

    /**
     * 5. Selesai Ujian (Hitung Nilai Akhir)
     */
    public function finish($examId)
    {
        $student = session('student');
        $exam = Exam::findOrFail($examId);

        $examResult = ExamResult::where('ujian_id', $examId)
            ->where('siswa_id', $student['id'])
            ->first();

        if (! $examResult || $examResult->status == 'Selesai') {
            return redirect()->route('student.dashboard');
        }

        // --- LOGIKA HITUNG NILAI ---
        // 1. Ambil Kunci Jawaban (Dari tabel questions)
        $questions = Question::where('question_set_id', $exam->question_set_id)->get();

        // 2. Ambil Jawaban Siswa
        $studentAnswers = StudentAnswer::where('hasil_id', $examResult->hasil_id)->get();

        $correctCount = 0;
        $totalQuestions = $questions->count();

        // 3. Bandingkan jawaban
        foreach ($studentAnswers as $ans) {
            $qs = $questions->find($ans->question_id);
            if ($qs && (int) $ans->jawaban_siswa === (int) $qs->answer_index) {
                $correctCount++;
            }
        }

        // 4. Rumus Nilai: (Benar / Total) * 100
        $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;

        // 5. Update Status & Nilai di Database
        $examResult->update([
            'status' => 'Selesai',
            'nilai' => $score,
            // 'waktu_selesai' => Carbon::now() // Opsional: timpa waktu selesai dengan waktu submit aktual
        ]);

        return redirect()->route('student.dashboard')
            ->with('status', 'Ujian selesai! Nilai Anda: '.number_format($score, 2));
    }
}

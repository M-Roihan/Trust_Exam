<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TeacherQuestionController extends Controller
{
    // Daftar Kelas (Disamakan dengan Admin agar konsisten)
    private const CLASS_LIST = [
        'X IPA', 'X IPS',
        'XI IPA', 'XI IPS',
        'XII IPA', 'XII IPS',
    ];

    /**
     * Halaman Utama: Menampilkan daftar bank soal milik guru.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Cek login guru
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        // Ambil data soal milik guru ini
        $questionSets = QuestionSet::with('teacher')
            ->withCount('questions')
            ->where('teacher_id', $teacher['id'])
            ->orderByDesc('updated_at')
            ->get();

        return view('guru.questions.index', compact('teacher', 'questionSets'));
    }

    /**
     * Halaman Step 1: Memilih Mapel, Semester, dan Kelas.
     */
    public function create(): View|RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        // Data statis untuk dropdown
        $subjects = ['Bahasa Indonesia', 'Matematika', 'Fisika', 'Biologi', 'Kimia', 'Bahasa Inggris'];
        $semesters = ['Ganjil', 'Genap'];
        $classes = self::CLASS_LIST; // Ambil dari konstanta

        return view('guru.questions.create', compact('teacher', 'subjects', 'semesters', 'classes'));
    }

    /**
     * Halaman Step 2: Builder Soal (Input Pertanyaan & Jawaban).
     * Digunakan untuk Create Baru maupun Edit.
     */
    public function builder(Request $request, ?QuestionSet $questionSet = null): View|RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        // Tentukan mode: Edit atau Create
        $mode = $questionSet ? 'edit' : $request->query('mode', 'create');

        // Ambil meta data (Mapel, Kelas, dll) dari URL (create) atau Database (edit)
        $meta = [
            'subject' => $request->query('subject', 'Bahasa Indonesia'),
            'exam_type' => $request->query('exam_type', 'UTS'),
            'semester' => $request->query('semester', 'Ganjil'),
            'class_level' => $request->query('class_level', 'X IPA'),
        ];

        $questions = [];

        // Jika sedang mengedit, muat soal lama dari database
        if ($questionSet) {
            // Keamanan: Cek apakah guru ini pemilik soal
            if ((int) $questionSet->teacher_id !== (int) $teacher['id']) {
                return redirect()->route('teacher.questions.index')->withErrors(['questions' => 'Anda tidak memiliki akses ke set soal tersebut.']);
            }

            $meta = [
                'subject' => $questionSet->subject,
                'exam_type' => $questionSet->exam_type,
                'semester' => $questionSet->semester,
                'class_level' => $questionSet->class_level,
            ];

            $questions = $questionSet->questions
                ->sortBy('order')
                ->values()
                ->map(fn (Question $question) => [
                    'prompt' => $question->prompt,
                    'options' => $question->options,
                    'answer' => $question->answer_index,
                ])
                ->toArray();
        }

        // Pertahankan inputan lama jika terjadi error validasi
        $questions = old('questions', $questions);

        // Jika kosong, siapkan 1 template soal kosong agar tampilan tidak rusak
        if (empty($questions)) {
            $questions = [[
                'prompt' => '',
                'options' => ['', '', '', '', ''],
                'answer' => 0,
            ]];
        }

        return view('guru.questions.builder', compact('teacher', 'mode', 'questionSet', 'meta', 'questions'));
    }

    /**
     * Menyimpan paket soal baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        $validated = $this->validateQuestionRequest($request);

        // Gunakan Transaksi Database: Semua tersimpan atau batal semua jika error
        DB::transaction(function () use ($validated, $teacher) {
            // 1. Simpan Header Soal (QuestionSet)
            $questionSet = QuestionSet::create([
                'teacher_id' => $teacher['id'],
                'subject' => $validated['subject'],
                'exam_type' => $validated['exam_type'],
                'semester' => $validated['semester'],
                'class_level' => $validated['class_level'],
                'description' => Arr::get($validated, 'description'),
            ]);

            // 2. Simpan Butir-butir Soal (Questions)
            foreach ($validated['questions'] as $index => $question) {
                $questionSet->questions()->create([
                    'prompt' => $question['prompt'],
                    'options' => $question['options'],
                    'answer_index' => $question['answer'],
                    'order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('teacher.questions.index')->with('status', 'Set soal baru berhasil disimpan.');
    }

    /**
     * Mengupdate paket soal yang sudah ada.
     */
    public function update(Request $request, QuestionSet $questionSet): RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        // Cek kepemilikan
        if ((int) $questionSet->teacher_id !== (int) $teacher['id']) {
            return redirect()->route('teacher.questions.index')->withErrors(['questions' => 'Anda tidak memiliki akses ke set soal tersebut.']);
        }

        $validated = $this->validateQuestionRequest($request);

        DB::transaction(function () use ($questionSet, $validated) {
            // 1. Update Header
            $questionSet->update([
                'subject' => $validated['subject'],
                'exam_type' => $validated['exam_type'],
                'semester' => $validated['semester'],
                'class_level' => $validated['class_level'],
                'description' => Arr::get($validated, 'description'),
            ]);

            // 2. Hapus semua soal lama & simpan ulang yang baru (Strategi Reset)
            $questionSet->questions()->delete();

            foreach ($validated['questions'] as $index => $question) {
                $questionSet->questions()->create([
                    'prompt' => $question['prompt'],
                    'options' => $question['options'],
                    'answer_index' => $question['answer'],
                    'order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('teacher.questions.index')->with('status', 'Set soal berhasil diperbarui.');
    }

    /**
     * Menghapus paket soal.
     */
    public function destroy(QuestionSet $questionSet): RedirectResponse
    {
        $teacher = $this->resolveTeacher();
        if (! $teacher) {
            return redirect()->route('login')->withErrors(['auth' => 'Sesi guru tidak ditemukan.']);
        }

        if ((int) $questionSet->teacher_id !== (int) $teacher['id']) {
            return redirect()->route('teacher.questions.index')->withErrors(['questions' => 'Anda tidak memiliki akses ke set soal tersebut.']);
        }

        $questionSet->delete();

        return redirect()->route('teacher.questions.index')->with('status', 'Set soal berhasil dihapus.');
    }

    // --- Helper Functions ---

    // Validasi input form soal (array multidimensi)
    private function validateQuestionRequest(Request $request): array
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:100'],
            'exam_type' => ['required', 'string', 'max:120'],
            'semester' => ['required', 'string', 'max:60'],
            'class_level' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.prompt' => ['required', 'string'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*' => ['required', 'string'],
            'questions.*.answer' => ['required', 'integer', 'min:0'],
        ]);

        // Cek logika: Kunci jawaban tidak boleh di luar jumlah opsi
        $questions = collect($data['questions'])
            ->map(function (array $question, int $index) {
                $options = array_values($question['options']);
                $answer = (int) $question['answer'];

                if ($answer >= count($options)) {
                    throw ValidationException::withMessages([
                        "questions.$index.answer" => 'Pilihan jawaban benar harus dipilih dari opsi yang tersedia.',
                    ]);
                }

                return [
                    'prompt' => $question['prompt'],
                    'options' => $options,
                    'answer' => $answer,
                ];
            })
            ->values()
            ->all();

        $data['questions'] = $questions;

        return $data;
    }

    // Mengambil data guru dari session
    private function resolveTeacher(): ?array
    {
        $teacher = session('teacher');

        if (! $teacher || ! array_key_exists('id', $teacher)) {
            return null;
        }

        return $teacher;
    }
}

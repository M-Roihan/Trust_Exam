<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Halaman Dashboard: Hanya Menu Navigasi
     */
    public function index(Request $request)
    {
        $student = session('student');
        if (!$student) return redirect()->route('login');

        // Definisi Menu Akses Cepat (Manual)
        $quickLinks = [
            [
                'label' => 'Daftar Ujian',
                'description' => 'Lihat jadwal dan kerjakan ujian.',
                'icon' => 'fas fa-file-signature text-primary',
                'href' => route('student.exams'), // Mengarah ke halaman List Ujian
            ],
            [
                'label' => 'Riwayat Nilai',
                'description' => 'Pantau hasil nilai ujianmu.',
                'icon' => 'fas fa-chart-line text-success',
                'href' => '#', // Nanti diarahkan ke halaman nilai
            ],
        ];

        $announcement = [
            'title' => 'Informasi Penting',
            'body'  => 'Selamat datang di panel ujian online.',
            'guidelines' => ['Wajib login 15 menit sebelum mulai.', 'Dilarang membuka tab lain.']
        ];

        return view('siswa.dashboard', compact('student', 'quickLinks', 'announcement'));
    }

    /**
     * Halaman Khusus Daftar Ujian (Real Data)
     */
    public function examList(Request $request)
    {
        $student = session('student');
        if (!$student) return redirect()->route('login');

        $kelasSiswa = $student['kelas'] ?? $student['class'] ?? null;
        if (!$kelasSiswa) return redirect()->route('login')->withErrors(['auth' => 'Data kelas error.']);

        // Ambil Data Ujian
        $exams = Exam::with(['questionSet', 'teacher'])
            ->whereHas('questionSet', function($query) use ($kelasSiswa) {
                $query->where('class_level', $kelasSiswa); 
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Cek Status
        $takenExams = ExamResult::where('siswa_id', $student['id'])
            ->pluck('status', 'ujian_id')
            ->toArray();

        // Return ke view khusus 'siswa.exams'
        return view('siswa.exam.exams', compact('student', 'exams', 'takenExams'));
    }
}
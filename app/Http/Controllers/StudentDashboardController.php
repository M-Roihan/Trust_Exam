<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard utama khusus Siswa.
     */
    public function index(Request $request): View
    {
        // 1. Ambil data siswa yang sedang login dari Session
        $student = $request->session()->get('student', [
            'name'     => 'Siswa Tamu',
            'role'     => 'Student',
            'initials' => 'ST',
            'class'    => null,
        ]);

        // 2. Definisi Menu Cepat (Quick Links)
        $quickLinks = [
            [
                'label'       => 'Daftar Ujian',
                'description' => 'Lihat jadwal dan kerjakan ujian yang tersedia.',
                'icon'        => 'fas fa-file-signature text-primary', // Ikon Ujian
                'href'        => '#', 
            ],
            [
                'label'       => 'Riwayat Nilai',
                'description' => 'Pantau hasil dan nilai ujian sebelumnya.',
                'icon'        => 'fas fa-chart-line text-success', // Ikon Nilai
                'href'        => '#',
            ],
        ];

        // 3. Data Pengumuman (Dummy/Statis)
        $announcement = [
            'title'      => 'Pengumuman Ujian',
            'body'       => 'Diberitahukan kepada seluruh siswa bahwa ujian akan dilaksanakan mulai ../../.. secara online.',
            'guidelines' => [
                'Wajib login 15 menit sebelum ujian dimulai.',
                'Setiap soal memiliki durasi pengerjaan otomatis (30 detik).',
                'Dilarang membuka tab lain selama ujian (Fitur Anti-Cheating aktif).',
                'Pastikan koneksi internet stabil.',
            ],
            'footer'     => 'Jika mengalami kendala teknis, segera hubungi Proktor atau Wali Kelas.',
        ];

        // Kirim semua data ke View
        return view('siswa.dashboard', compact('student', 'quickLinks', 'announcement'));
    }
}
@extends('layouts.teacher')

@section('title', 'Dashboard Guru')

@section('content')
<div class="container-fluid">
    <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center p-4 mb-4" role="alert">
        <div class="me-3 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="fas fa-chalkboard-teacher fa-lg"></i>
        </div>
        <div>
            <h4 class="alert-heading mb-1">Selamat Datang, {{ session('teacher.name') }}!</h4>
            <p class="mb-0">Siap untuk mengelola ujian hari ini?</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('teacher.questions.index') }}" class="card text-decoration-none h-100 shadow-sm border-0 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-book text-primary fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Bank Soal</h6>
                    <p class="small text-muted mb-0">Buat & kelola soal ujian</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('teacher.exams.index') }}" class="card text-decoration-none h-100 shadow-sm border-0 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-alt text-success fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Jadwal Ujian</h6>
                    <p class="small text-muted mb-0">Atur waktu pelaksanaan</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="card text-decoration-none h-100 shadow-sm border-0 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-poll text-info fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Hasil Ujian</h6>
                    <p class="small text-muted mb-0">Lihat nilai & analisis</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="card text-decoration-none h-100 shadow-sm border-0 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-users text-warning fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Data Siswa</h6>
                    <p class="small text-muted mb-0">Daftar siswa aktif</p>
                </div>
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0"><i class="fas fa-bullhorn text-danger me-2"></i>Pengumuman Penting</h5>
        </div>
        <div class="card-body">
    <div class="border-start border-4 border-danger ps-3">
        <h6 class="fw-bold">Persiapan Ujian Akhir Semester (UAS)</h6>
        <p class="text-muted mb-2">
            Pelaksanaan UAS akan dimulai pada <strong>.../.../...</strong>.
            Mohon Bapak/Ibu Guru memperhatikan beberapa ketentuan berikut:
        </p>
        <ul class="mb-0 text-muted small">
            <li>Soal ujian wajib di-upload maksimal H-1 sebelum pelaksanaan ujian.</li>
            <li>Pastikan soal telah diperiksa kembali sebelum di-upload.</li>
            <li>Format soal harus sesuai ketentuan yang ditetapkan sekolah.</li>
            <li>Apabila terdapat kendala, segera hubungi Admin atau panitia Ujian</li>
        </ul>
    </div>
</div>

    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s; }
    .hover-lift:hover { transform: translateY(-5px); }
</style>
@endsection
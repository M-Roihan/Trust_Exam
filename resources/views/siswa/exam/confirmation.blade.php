@extends('layouts.student')

@section('title', 'Konfirmasi Ujian')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-file-contract me-2"></i>Konfirmasi Ujian</h4>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-laptop-code fa-3x text-primary"></i>
                        </div>
                        <h2 class="fw-bold text-dark">{{ $exam->nama_ujian }}</h2>
                        <span class="badge bg-info text-dark fs-6">{{ $exam->questionSet->subject }}</span>
                    </div>

                    <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <strong>Perhatian!</strong>
                            <p class="mb-0">Waktu akan langsung berjalan begitu Anda menekan tombol "Mulai Mengerjakan". Jangan menutup browser selama ujian berlangsung.</p>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted"><i class="fas fa-chalkboard-teacher me-2"></i> Guru Pengampu</span>
                            <strong class="text-dark">{{ $exam->teacher->nama_guru }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted"><i class="fas fa-list-ol me-2"></i> Jumlah Soal</span>
                            <strong class="text-dark">{{ $exam->questionSet->questions()->count() }} Butir</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted"><i class="fas fa-clock me-2"></i> Durasi Pengerjaan</span>
                            <strong class="text-dark">{{ $exam->durasi }} Menit</strong>
                        </li>
                    </ul>

                    <div class="d-grid gap-2">
                        <a href="{{ route('student.exam.start', $exam->ujian_id) }}" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-play-circle me-2"></i> Mulai Mengerjakan
                        </a>
                        <a href="{{ route('student.exams') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
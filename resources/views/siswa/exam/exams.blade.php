@extends('layouts.student')

@section('title', 'Daftar Ujian')

@section('content')
<div class="container-fluid">
    <div class="mt-4 mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-file-signature me-2"></i>Daftar Ujian</h3>
        <p class="text-muted">Berikut adalah jadwal ujian yang tersedia untuk kelas {{ $student['kelas'] ?? '' }}.</p>
    </div>

    <div class="row g-4 mb-5">
        @if($exams->isEmpty())
            <div class="col-12">
                <div class="card border-0 shadow-sm p-5 text-center">
                    <div class="mb-3 text-muted" style="opacity: 0.5;">
                        <i class="fas fa-mug-hot fa-4x"></i>
                    </div>
                    <h5 class="fw-bold">Tidak Ada Jadwal Ujian</h5>
                    <p class="text-muted">Saat ini belum ada ujian aktif.</p>
                </div>
            </div>
        @else
            @foreach ($exams as $exam)
                @php
                    $now = \Carbon\Carbon::now();
                    $start = \Carbon\Carbon::parse($exam->tanggal_mulai);
                    $end = \Carbon\Carbon::parse($exam->tanggal_selesai);
                    $status = $takenExams[$exam->ujian_id] ?? null;
                    $isOpen = $now->between($start, $end);
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-up">
                        <div class="card-body p-4">
                            {{-- Badge Status --}}
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-secondary">{{ $exam->questionSet->subject ?? 'Mapel' }}</span>
                                @if($status == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($status == 'Sedang Dikerjakan')
                                    <span class="badge bg-warning text-dark">Berjalan</span>
                                @elseif($isOpen)
                                    <span class="badge bg-primary">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tutup</span>
                                @endif
                            </div>

                            <h5 class="fw-bold text-dark mb-1">{{ $exam->nama_ujian }}</h5>
                            <p class="text-muted small mb-3"><i class="fas fa-clock me-1"></i> {{ $exam->durasi }} Menit</p>

                            <div class="alert alert-light border p-2 mb-3 small">
                                <strong>Mulai:</strong> {{ $start->format('d M H:i') }} <br>
                                <strong>Selesai:</strong> {{ $end->format('d M H:i') }}
                            </div>

                            <div class="d-grid">
                                @if($status == 'Selesai')
                                    <button class="btn btn-success disabled" disabled>Sudah Dikerjakan</button>
                                @elseif($status == 'Sedang Dikerjakan')
                                    <a href="{{ route('student.exam.show', ['exam' => $exam->ujian_id]) }}" class="btn btn-warning">Lanjutkan</a>
                                @elseif($isOpen)
                                    <a href="{{ route('student.exam.confirmation', $exam->ujian_id) }}" class="btn btn-primary">Kerjakan</a>
                                @elseif($now < $start)
                                    <button class="btn btn-secondary disabled" disabled>Belum Dibuka</button>
                                @else
                                    <button class="btn btn-outline-danger disabled" disabled>Waktu Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<style>
    .hover-up { transition: transform 0.2s; }
    .hover-up:hover { transform: translateY(-5px); }
</style>
@endsection
@extends('layouts.teacher') 

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Jadwal Ujian</h1>
    
    <div class="d-flex justify-content-between mb-3">
        <p class="text-muted">Kelola jadwal ujian untuk siswa.</p>
        <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary">
            + Buat Jadwal Baru
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Ujian Aktif
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ujian</th>
                        <th>Paket Soal</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Selesai</th>
                        <th>Durasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $index => $exam)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $exam->nama_ujian }}</td>
                        <td>
                            @if($exam->questionSet)
                                <span class="badge bg-info text-dark">{{ $exam->questionSet->subject }} - {{ $exam->questionSet->class_level }}</span>
                            @else
                                <span class="text-danger">Paket Soal Terhapus</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($exam->tanggal_mulai)->format('d M Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($exam->tanggal_selesai)->format('d M Y H:i') }}</td>
                        <td>{{ $exam->durasi }} Menit</td>
                        <td>
                            <form action="{{ route('teacher.exams.destroy', $exam->ujian_id) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada jadwal ujian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
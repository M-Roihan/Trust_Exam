@extends('layouts.teacher')

@section('title', 'Hasil Ujian Siswa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1 class="h3 mb-0">Hasil Ujian Siswa</h1>
        {{-- TOMBOL EKSPOR --}}
        <a href="{{ route('teacher.exams.export.results') }}" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i> Ekspor ke Excel
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="fas fa-poll me-1"></i> Rekap Nilai Semua Siswa
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Nama Ujian</th>
                        <th>Mata Pelajaran</th>
                        <th>Tanggal</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $key => $result)
                        <tr>
                            <td>{{ $results->firstItem() + $key }}</td>
                            <td>{{ $result->student->nis ?? '-' }}</td>
                            <td class="fw-bold">{{ $result->student->nama_siswa ?? '-' }}</td>
                            <td>{{ $result->student->kelas ?? '-' }}</td>
                            <td>{{ $result->exam->nama_ujian ?? '-' }}</td>
                            <td>{{ $result->exam->questionSet->subject ?? '-' }}</td>
                            <td>{{ $result->updated_at->format('d M Y, H:i') }}</td>
                            <td class="text-center fw-bold">
                                @if ($result->nilai >= 75)
                                    <span class="text-success">{{ $result->nilai }}</span>
                                @else
                                    <span class="text-danger">{{ $result->nilai }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Selesai</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data hasil ujian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $results->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
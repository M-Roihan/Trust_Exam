@extends('layouts.teacher')

@section('title', 'Hasil Ujian Siswa')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hasil Ujian Siswa</h1>

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
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data hasil ujian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $results->links() }}
        </div>
    </div>
</div>
@endsection

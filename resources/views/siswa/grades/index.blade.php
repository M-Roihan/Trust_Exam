@extends('layouts.student')

@section('title', 'Riwayat Nilai')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Riwayat Hasil Ujian</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-history me-1"></i> Daftar Nilai Saya
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Ujian</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $key => $result)
                            <tr>
                                <td>{{ $results->firstItem() + $key }}</td>

                                {{-- Nama Ujian --}}
                                <td class="fw-bold">{{ $result->exam->nama_ujian ?? '-' }}</td>

                                {{-- Mapel --}}
                                <td>{{ $result->exam->questionSet->subject ?? '-' }}</td>

                                {{-- Tanggal --}}
                                <td>{{ $result->updated_at->format('d M Y, H:i') }}</td>

                                {{-- Nilai --}}
                                <td class="text-center fw-bold fs-5">
                                    @if ($result->nilai >= 75)
                                        <span class="text-success">{{ number_format($result->nilai, 0) }}</span>
                                    @else
                                        <span class="text-danger">{{ number_format($result->nilai, 0) }}</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="text-center">
                                    <span class="badge bg-success">Selesai</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Belum ada riwayat ujian.
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
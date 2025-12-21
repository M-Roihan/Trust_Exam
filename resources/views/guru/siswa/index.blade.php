@extends('layouts.teacher')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Siswa</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i> Daftar Siswa
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student as $key => $s)
                    <tr>
                        <td>{{ $student->firstItem() + $key }}</td>
                        
                        {{-- NIS --}}
                        <td>{{ $s->nis ?? '-' }}</td>

                        {{-- NAMA --}}
                        <td>{{ $s->nama_siswa }}</td>
                        
                        {{-- KELAS --}}
                        <td>{{ $s->kelas ?? '-' }}</td>

                        {{-- JENIS KELAMIN --}}
                        <td>{{ $s->jenis_kelamin ?? '-' }}</td>

                         {{-- ALAMAT --}}
                        <td>{{ $s->alamat ?? '-' }}</td>

                        {{-- STATUS --}}
                        <td>
                            @if(strtolower($s->status) == 'aktif' || $s->status == '1')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- Pagination --}}
            <div class="mt-3">
                {{ $student->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Kelola Akun Pengguna')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">Kelola Akun</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ALERT STATUS --}}
    @if (session('status'))
        <div class="alert alert-success shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        {{-- FILTER --}}
        <div class="card-body border-bottom pb-3">
            <form action="{{ route('admin.users.index') }}" method="get" class="row g-2">

                <div class="col-md-4">
                    <select name="role" class="form-select shadow-sm rounded-3">
                        @foreach (['Siswa', 'Guru', 'Admin'] as $roleOption)
                            <option value="{{ $roleOption }}" 
                                {{ $activeRole === $roleOption ? 'selected' : '' }}>
                                {{ $roleOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary w-100 rounded-3 shadow-sm">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="{{ route('admin.users.create', ['role' => $activeRole]) }}"
                       class="btn btn-success w-100 rounded-3 shadow-sm">
                        <i class="fas fa-plus"></i> Tambah {{ $activeRole }}
                    </a>
                </div>

            </form>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        @foreach ($tableHeaders as $header)
                            <th class="text-muted small">{{ $header }}</th>
                        @endforeach

                        @if(!in_array('Aksi', $tableHeaders))
                            <th class="text-center text-muted small">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tableRows as $row)
                        <tr>
                            <td>{{ $row['no'] }}</td>
                            <td class="fw-semibold">{{ $row['name'] }}</td>
                            <td><span class="badge bg-secondary-subtle text-dark">{{ $row['username'] }}</span></td>
                            <td class="text-muted small"><em>{{ $row['password'] }}</em></td>
                            <td>{{ $row['role'] }}</td>

                            {{-- BADGE STATUS COLORFUL --}}
                            <td>
                                @php
                                    $color = [
                                        'aktif' => 'success',
                                        'nonaktif' => 'danger'
                                    ][$row['status_class'] ?? 'aktif'] ?? 'secondary';
                                @endphp

                                <span class="badge bg-{{ $color }}">{{ $row['status'] }}</span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', ['role' => $row['role'], 'id' => $row['id'], 'redirect' => 'index']) }}" 
                                   class="btn btn-sm btn-warning text-white rounded-3 me-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', ['role' => $row['role'], 'id' => $row['id']]) }}"
                                      class="d-inline" method="post"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect" value="index" />
                                    <button class="btn btn-sm btn-danger rounded-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center p-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white text-muted small">
            Menampilkan data {{ strtolower($activeRole) }}.
        </div>
    </div>

</div>
@endsection
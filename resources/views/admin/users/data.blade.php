@extends('layouts.admin')

@section('title', 'Kelola Data Pengguna')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-primary">Data Pengguna</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- STATUS --}}
    @if (session('status'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- CONTENT CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        {{-- FILTER SIMPLE --}}
        <div class="card-body border-bottom pb-3">
            <form action="{{ route('admin.users.data') }}" method="get" class="row g-2">

                <div class="col-md-4">
                    <select name="role" class="form-select shadow-sm rounded-3">
                        @foreach (['Siswa', 'Guru', 'Admin'] as $roleOption)
                            <option value="{{ $roleOption }}" {{ $activeRole === $roleOption ? 'selected' : '' }}>
                                {{ $roleOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100 rounded-3 shadow-sm">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        @foreach ($tableHeaders as $header)
                            <th class="small text-muted">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tableRows as $row)
                        <tr>
                            @foreach ($row['data'] as $value)
                                <td>{{ $value }}</td>
                            @endforeach

                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', ['role' => $activeRole, 'id' => $row['id'], 'redirect' => 'data']) }}"
                                   class="btn btn-sm btn-warning text-white rounded-3 me-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', ['role' => $activeRole, 'id' => $row['id']]) }}"
                                      method="post" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger rounded-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($tableHeaders) + 1 }}" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white text-muted small">
            Menampilkan data peran: <strong>{{ $activeRole }}</strong>
        </div>

    </div>
</div>
@endsection
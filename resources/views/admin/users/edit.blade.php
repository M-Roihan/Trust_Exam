@extends('layouts.admin')

@section('title', "Ubah Data $formRole")

@section('content')
@php
    $redirectTarget = $redirectTarget ?? 'index';
    $routeParams = ['role' => $formRole, 'id' => $entity->getKey()];
    $backRoute = $redirectTarget === 'data'
        ? route('admin.users.data', ['role' => $formRole])
        : route('admin.users.index', ['role' => $formRole]);
@endphp

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-header py-4 text-white rounded-top"
                     style="background: linear-gradient(90deg, #6a11cb, #2575fc);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0">Ubah Data {{ $formRole }}</h4>
                            <small class="opacity-75">Perbarui informasi berikut dengan tepat</small>
                        </div>
                        <a href="{{ $backRoute }}" class="btn btn-light btn-sm fw-semibold px-3 rounded-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('admin.users.update', $routeParams) }}" method="post">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="redirect" value="{{ $redirectTarget }}">

                        <div class="alert rounded-3 text-white border-0 mb-4"
                             style="background: linear-gradient(45deg, #ff9966, #ff5e62);">
                            <strong>📝 Informasi {{ $formRole }}</strong>
                        </div>

                        <div class="row g-3">

                            {{-- ================= FORM GURU ================= --}}
                            @if ($formRole === 'Guru')

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap *</label>
                                    <input type="text" name="nama_guru"
                                           class="form-control form-control-lg rounded-3 @error('nama_guru') is-invalid @enderror"
                                           value="{{ old('nama_guru', $entity->nama_guru) }}" required>
                                    @error('nama_guru') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mata Pelajaran *</label>
                                    <select name="matapelajaran" class="form-select form-select-lg rounded-3 @error('matapelajaran') is-invalid @enderror" required>
                                        <option value="" disabled>Pilih Mapel...</option>
                                        @foreach ($subjectList as $subject)
                                            <option value="{{ $subject }}" {{ old('matapelajaran', $entity->matapelajaran) === $subject ? 'selected' : '' }}>
                                                {{ $subject }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('matapelajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            {{-- ================= FORM ADMIN ================= --}}
                            @elseif ($formRole === 'Admin')

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Nama Admin *</label>
                                    <input type="text" name="nama_admin"
                                           class="form-control form-control-lg rounded-3 @error('nama_admin') is-invalid @enderror"
                                           value="{{ old('nama_admin', $entity->nama_admin) }}" required>
                                    @error('nama_admin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            {{-- ================= FORM SISWA ================= --}}
                            @else

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Siswa *</label>
                                    <input type="text" name="nama_siswa"
                                           class="form-control form-control-lg rounded-3 @error('nama_siswa') is-invalid @enderror"
                                           value="{{ old('nama_siswa', $entity->nama_siswa) }}" required>
                                    @error('nama_siswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIS *</label>
                                    <input type="text" name="nis"
                                           class="form-control form-control-lg rounded-3 @error('nis') is-invalid @enderror"
                                           value="{{ old('nis', $entity->nis) }}" required>
                                    @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin"
                                            class="form-select form-select-lg rounded-3 @error('jenis_kelamin') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih...</option>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender }}" {{ old('jenis_kelamin', $entity->jenis_kelamin) === $gender ? 'selected' : '' }}>
                                                {{ $gender }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Kelas *</label>
                                    <select name="kelas"
                                            class="form-select form-select-lg rounded-3 @error('kelas') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih...</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas }}" {{ old('kelas', $entity->kelas) === $kelas ? 'selected' : '' }}>
                                                {{ $kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Status *</label>
                                    <select name="status"
                                            class="form-select form-select-lg rounded-3 @error('status') is-invalid @enderror"
                                            required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $entity->status) === $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir"
                                           class="form-control form-control-lg rounded-3 @error('tempat_lahir') is-invalid @enderror"
                                           value="{{ old('tempat_lahir', $entity->tempat_lahir) }}" required>
                                    @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir"
                                           class="form-control form-control-lg rounded-3 @error('tanggal_lahir') is-invalid @enderror"
                                           value="{{ old('tanggal_lahir', $entity->tanggal_lahir?->format('Y-m-d')) }}" required>
                                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat Lengkap *</label>
                                    <textarea name="alamat" rows="2"
                                              class="form-control form-control-lg rounded-3 @error('alamat') is-invalid @enderror"
                                              required>{{ old('alamat', $entity->alamat) }}</textarea>
                                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            @endif

                        </div>

                        <div class="alert mt-4 rounded-3 text-white border-0"
                             style="background: linear-gradient(45deg, #00c6ff, #0072ff);">
                            <strong>🔐 Akun Login</strong>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Username *</label>
                                <input type="text" name="username"
                                       class="form-control form-control-lg rounded-3"
                                       value="{{ old('username', $entity->username) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <input type="password" name="password"
                                       class="form-control form-control-lg rounded-3"
                                       placeholder="Biarkan kosong jika tidak mengubah password">
                                <small class="text-muted fst-italic ms-1">*Isi hanya jika ingin mengganti password</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn text-white px-4 py-2 fw-semibold rounded-3"
                                    style="background: linear-gradient(90deg, #6a11cb, #2575fc); border: none;">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</div>
@endsection
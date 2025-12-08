@extends('layouts.admin')

@section('title', "Tambah Data $formRole")

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- CARD WRAPPER -->
            <div class="card shadow border-0 rounded-4">

                <!-- HEADER COLORFUL -->
                <div class="card-header rounded-top text-white py-4"
                     style="background: linear-gradient(90deg, #6a11cb, #2575fc);">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-user-plus me-2"></i> Tambah Data {{ $formRole }}
                    </h4>
                    <small class="opacity-75">Isi semua data dengan benar untuk melanjutkan</small>
                </div>

                <!-- CARD BODY -->
                <div class="card-body p-4">

                    <form action="{{ route('admin.users.store') }}" method="post">
                        @csrf

                        <input type="hidden" name="role_type" value="{{ $formRole }}">

                        <!-- SECTION INFORMASI -->
                        <div class="alert rounded-3 text-white border-0 mb-4"
                             style="background: linear-gradient(45deg, #ff9966, #ff5e62);">
                            <strong>📝 Informasi {{ $formRole }}</strong>
                        </div>

                        <div class="row g-3">

                            {{-- === FORM GURU === --}}
                            @if ($formRole === 'Guru')

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap *</label>
                                    <input type="text" name="nama_guru" class="form-control form-control-lg rounded-3"
                                           placeholder="Masukkan nama guru..." required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                    
                                    <select name="matapelajaran" class="form-select form-select-lg rounded-3 @error('matapelajaran') is-invalid @enderror" required>
                                        <option value="" disabled selected>Pilih Mapel...</option>
                                        @foreach ($subjectList as $subject)
                                            <option value="{{ $subject }}" {{ old('matapelajaran') === $subject ? 'selected' : '' }}>
                                                {{ $subject }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    @error('matapelajaran') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>

                            {{-- === FORM ADMIN === --}}
                            @elseif ($formRole === 'Admin')

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Nama Admin *</label>
                                    <input type="text" name="nama_admin" class="form-control form-control-lg rounded-3"
                                           placeholder="Masukkan nama admin..." required>
                                </div>

                            {{-- === FORM SISWA === --}}
                            @else

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap *</label>
                                    <input type="text" name="nama_siswa" class="form-control form-control-lg rounded-3"
                                           placeholder="Masukkan nama siswa..." required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIS *</label>
                                    <input type="text" name="nis" class="form-control form-control-lg rounded-3"
                                           placeholder="Nomor induk siswa" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Kelas *</label>
                                    <select class="form-select form-select-lg rounded-3" name="kelas" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas }}">{{ $kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Jenis Kelamin *</label>
                                    <select class="form-select form-select-lg rounded-3" name="jenis_kelamin" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender }}">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Status *</label>
                                    <select class="form-select form-select-lg rounded-3" name="status" required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" class="form-control form-control-lg rounded-3"
                                           placeholder="Contoh: Bandung" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" class="form-control form-control-lg rounded-3" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat *</label>
                                    <textarea class="form-control form-control-lg rounded-3" rows="2" name="alamat"
                                              placeholder="Masukkan alamat..." required></textarea>
                                </div>

                            @endif

                        </div>

                        <!-- SECTION AKUN -->
                        <div class="alert mt-4 rounded-3 text-white border-0"
                             style="background: linear-gradient(45deg, #00c6ff, #0072ff);">
                            <strong>🔐 Akun Login</strong>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Username *</label>
                                <input type="text" name="username" class="form-control form-control-lg rounded-3"
                                       placeholder="Buat username..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password *</label>
                                <input type="password" name="password" class="form-control form-control-lg rounded-3"
                                       placeholder="Buat password..." required>
                            </div>
                        </div>

                        <!-- BUTTON ACTION -->
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="{{ route('admin.users.index', ['role' => $formRole]) }}"
                               class="btn btn-light border rounded-3 px-4 py-2">
                                Batal
                            </a>

                            <button class="btn text-white rounded-3 px-4 py-2 fw-semibold"
                                    style="background: linear-gradient(90deg, #6a11cb, #2575fc); border: none;">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>

                        </div>

                    </form>

                </div> <!-- card-body -->
            </div> <!-- card -->

        </div>
    </div>

</div>
@endsection
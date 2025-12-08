@extends('layouts.teacher')

@section('title', 'Buat Paket Soal Baru')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Pengaturan Paket Soal</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('teacher.questions.builder') }}" method="get">
                        
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="subject" class="form-select" required>
                                <option value="" disabled selected>Pilih Mapel...</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject }}">{{ $subject }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Ujian</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="exam_type" value="UTS" id="uts" required>
                                    <label class="form-check-label" for="uts">UTS (Tengah Semester)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="exam_type" value="UAS" id="uas" required>
                                    <label class="form-check-label" for="uas">UAS (Akhir Semester)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select" required>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <select name="class_level" class="form-select" required>
                                    <option value="" disabled selected>Pilih Kelas...</option>
                                    @foreach ($classes as $kelas)
                                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.questions.index') }}" class="btn btn-light text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Lanjut Buat Soal <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
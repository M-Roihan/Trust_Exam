@extends('layouts.teacher')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Buat Jadwal Ujian Baru</h1>
    
    <div class="card mb-4" style="max-width: 800px;">
        <div class="card-header">Form Jadwal Ujian</div>
        <div class="card-body">
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
                </div>
            @endif

            <form action="{{ route('teacher.exams.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nama Ujian (Label)</label>
                    <input type="text" name="nama_ujian" class="form-control" placeholder="Contoh: UH 1 Bahasa Indonesia X IPA" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Paket Soal</label>
                    <select name="question_set_id" class="form-select" required>
                        <option value="">-- Pilih Paket Soal --</option>
                        @foreach($questionSets as $set)
                            <option value="{{ $set->id }}">
                                {{ $set->subject }} - {{ $set->class_level }} ({{ $set->exam_type }}) - {{ $set->questions_count ?? 0 }} Soal
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hanya paket soal yang sudah Anda buat yang muncul di sini.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" name="tanggal_mulai" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Selesai (Batas Akhir)</label>
                        <input type="datetime-local" name="tanggal_selesai" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Durasi Pengerjaan (Menit)</label>
                    <input type="number" name="durasi" class="form-control" placeholder="60" min="1" required>
                </div>

                <div class="mt-4">
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
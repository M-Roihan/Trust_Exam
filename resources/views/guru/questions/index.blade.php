@extends('layouts.teacher')

@section('title', 'Manajemen Soal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Bank Soal</h3>
        <a href="{{ route('teacher.questions.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Buat Paket Soal
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($questionSets->isEmpty())
        <div class="text-center py-5">
            <img src="{{ asset('assets/img/icon-question.svg') }}" alt="Empty" style="width: 150px; opacity: 0.5;">
            <p class="text-muted mt-3">Belum ada paket soal yang dibuat.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach ($questionSets as $set)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-info text-dark">{{ $set->subject }}</span>
                                <small class="text-muted">{{ $set->created_at->format('d M Y') }}</small>
                            </div>
                            <h5 class="card-title fw-bold mb-3">{{ $set->exam_type }} - {{ $set->semester }}</h5>
                            
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fas fa-user-graduate me-2"></i> {{ $set->class_level }}
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('teacher.questions.edit', $set) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('teacher.questions.destroy', $set) }}" method="post" onsubmit="return confirm('Hapus paket soal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
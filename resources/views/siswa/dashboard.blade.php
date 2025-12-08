@extends('layouts.student')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid">
    
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h5 class="text-muted mb-1">Selamat datang kembali,</h5>
            <h1 class="fw-bold text-dark display-5">{{ $student['name'] }}</h1>
            <div class="d-flex gap-2 mt-2">
                @if (! empty($student['class']))
                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-graduation-cap me-1"></i> Kelas {{ $student['class'] }}</span>
                @endif
                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $student['role'] }}</span>
            </div>
        </div>
        <div class="col-md-4 text-md-end d-none d-md-block">
            <div class="d-inline-flex align-items-center justify-content-center bg-white text-success shadow rounded-circle fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem; border: 4px solid #ccfbf1;">
                {{ $student['initials'] }}
            </div>
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bolt text-warning me-2"></i>Akses Cepat</h5>
    <div class="row g-4 mb-5">
        @foreach ($quickLinks as $link)
            <div class="col-md-6 col-lg-4">
                <a href="{{ $link['href'] }}" class="card h-100 border-0 shadow-sm hover-up text-decoration-none">
                    <div class="card-body p-4 d-flex align-items-start">
                        <div class="bg-light p-3 rounded me-3">
                            <img src="{{ $link['icon'] }}" alt="Icon" width="40" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <i class="fas fa-link fa-2x text-primary" style="display:none;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $link['label'] }}</h5>
                            <p class="text-muted small mb-0">{{ $link['description'] }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow overflow-hidden">
        <div class="card-header bg-warning text-dark py-3 d-flex align-items-center">
            <i class="fas fa-bullhorn fa-lg me-2"></i>
            <h5 class="mb-0 fw-bold">Papan Pengumuman</h5>
        </div>
        <div class="card-body p-4 bg-white">
            <h4 class="text-primary fw-bold mb-3">{{ $announcement['title'] }}</h4>
            <p class="lead" style="font-size: 1rem;">{{ $announcement['body'] }}</p>
            
            @if(!empty($announcement['guidelines']))
                <div class="alert alert-info border-0 d-flex mt-4" role="alert">
                    <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                    <div>
                        <h6 class="fw-bold">Ketentuan Ujian:</h6>
                        <ul class="mb-0 ps-3">
                            @foreach ($announcement['guidelines'] as $guideline)
                                <li>{{ $guideline }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <hr>
            <small class="text-muted fst-italic">
                <i class="fas fa-signature me-1"></i> {{ $announcement['footer'] }}
            </small>
        </div>
    </div>

</div>

<style>
    .hover-up { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-up:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1)!important; }
</style>
@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Guru - TrustExam')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/teacher.css') }}">

</head>
<body>

<div class="d-flex">
    <div class="sidebar p-3 d-none d-lg-block" style="width: 260px;">
        <h4 class="text-white mb-4 ps-2"><i class="fas fa-graduation-cap me-2"></i>TrustExam</h4>
        
        <small class="text-white-50 text-uppercase ps-2" style="font-size: 0.75rem;">Menu Utama</small>
        <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
        <a href="{{ route('teacher.questions.index') }}" class="{{ request()->routeIs('teacher.questions.*') ? 'active' : '' }}">
            <i class="fas fa-book-open me-2"></i> Bank Soal
        </a>
        
        <small class="text-white-50 text-uppercase ps-2 mt-3 d-block" style="font-size: 0.75rem;">Ujian</small>
        <a href="{{ route('teacher.exams.index') }}" class="{{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt me-2"></i> Jadwal Ujian
        </a>
        <a href="#"><i class="fas fa-chart-bar me-2"></i> Hasil Ujian</a>
        
        <hr class="text-white-50 mt-4">
        
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100 btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <div class="w-100">
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <button class="btn btn-link d-lg-none"><i class="fas fa-bars"></i></button>
            <span class="navbar-brand mb-0 h1 ms-2">Panel Guru</span>
            
            <div class="ms-auto d-flex align-items-center">
                <div class="text-end me-3">
                    <small class="d-block text-end">
                        Guru {{ session('teacher.subject') }}
                    </small>
                    <span class="fw-bold">{{ session('teacher.name', 'Bapak/Ibu Guru') }}</span>
                </div>
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    {{ session('teacher.initials', 'G') }}
                </div>
            </div>
        </nav>

        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
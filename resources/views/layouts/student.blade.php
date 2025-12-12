<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Area Siswa - TrustExam')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/student-dashboard.css') }}">

</head>
<body>

<div class="d-flex">
    <div id="sidebar" class="sidebar p-4 d-none d-lg-block" style="width: 280px;">
        <div class="d-flex align-items-center mb-5">
            <i class="fas fa-shapes fa-2x me-2"></i>
            <h4 class="mb-0 fw-bold">TrustExam</h4>
        </div>
        
        <small class="text-uppercase text-white-50 mb-2 d-block" style="font-size: 0.75rem;">Menu Ujian</small>
        
        <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home me-2"></i> Beranda
        </a>
        
         <a href="{{ route('student.exams') }}" class="nav-link">
            <i class="fas fa-file-alt me-2"></i> Daftar Ujian
        </a>

        <a href="#">
            <i class="fas fa-history me-2"></i> Riwayat Nilai
        </a>
        
        <div class="mt-5 p-3 rounded" style="background: rgba(0,0,0,0.1);">
            <div class="d-flex align-items-center">
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                   <i class="fas fa-user"></i>
                </div>
                <div>
                    <small class="d-block text-white-50">Login sebagai:</small>
                    <span class="fw-bold d-block">Siswa</span>
                </div>
            </div>
            <hr class="border-white-50 my-3">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger w-100 shadow-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="w-100">
        <nav class="navbar navbar-light bg-white shadow-sm d-lg-none px-3 mb-3">
            <span class="navbar-brand fw-bold text-success">TrustExam Siswa</span>
            <button class="btn btn-outline-success" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </nav>

        <div class="content">
            @yield('content')
        </div>
        
        <footer class="text-center text-muted mt-5 small">
            &copy; {{ date('Y') }} TrustExam. Selamat Belajar & Semangat Ujian!
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('d-none');
        
        // Agar sidebar melayang (floating) saat di HP
        if (!sidebar.classList.contains('d-none') && window.innerWidth < 992) {
            sidebar.style.position = 'fixed';
            sidebar.style.zIndex = '9999';
            sidebar.style.top = '0';
            sidebar.style.left = '0';
            sidebar.style.height = '100%';
        } else {
            sidebar.style.position = '';
        }
    }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ujian Online') - TrustExam</title>
    
    {{-- CSS Bootstrap & FontAwesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS Tambahan agar lebih nyaman di mata */
        body { background-color: #f5f7fa; }
        .exam-container { max-width: 1200px; margin: 0 auto; }
        /* Mencegah seleksi teks (Anti-Cheating Visual) */
        body { user-select: none; -webkit-user-select: none; }
    </style>
</head>
<body>

    {{-- Navbar Minimalis (Hanya Logo & Identitas) --}}
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1 fw-bold text-primary">
                <i class="fas fa-graduation-cap me-2"></i>TrustExam
            </span>
            
            <div class="d-flex align-items-center">
                <span class="text-muted small me-2">Sedang Mengerjakan:</span>
                <span class="fw-bold text-dark">{{ session('student.nama_siswa') }}</span>
            </div>
        </div>
    </nav>

    {{-- Area Konten Ujian --}}
    <div class="exam-container px-3">
        @yield('content')
    </div>

    {{-- Script Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Stack untuk script khusus (timer, dll) --}}
    @stack('scripts')

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TrustExam Admin')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

</head>
<body>

<div class="d-flex">
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-white mb-4">TrustExam</h4>
        
        <a href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i> Kelola Akun</a>
        <a href="{{ route('admin.users.data') }}"><i class="fas fa-database me-2"></i> Data Pengguna</a>
        
        <hr class="text-white">
        
        <form action="{{ route('logout') }}" method="post" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100 btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <div class="w-100">
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <span class="navbar-brand mb-0 h1">Dashboard</span>
            <div class="d-flex align-items-center">
                <div class="me-2 text-end">
                    <small class="d-block text-muted">Admin</small>
                    <strong>{{ session('admin.name', 'Admin System') }}</strong>
                </div>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center ms-2" style="width: 40px; height: 40px;">
                    {{ session('admin.initials', 'AS') }}
                </div>
            </div>
        </nav>

        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    (function () {
        const retainCurrent = () => {
            history.replaceState(null, document.title, location.href);
            history.pushState(null, document.title, location.href);
        };
        window.addEventListener("popstate", () => {
            retainCurrent();
            history.go(1);
        });
        retainCurrent();
    })();
</script>

@stack('scripts') </body>
</html>
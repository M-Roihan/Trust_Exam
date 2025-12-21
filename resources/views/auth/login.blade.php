<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TrustExam') }} — Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">

        <div class="col-lg-10">
            <div class="row shadow bg-white rounded-4 overflow-hidden">

                <!-- KIRI (ILUSTRASI) -->
                <div class="col-lg-5 d-flex flex-column justify-content-center align-items-center p-4"
                     style="background-color: #f5faff;">
                    
                    <h4 class="fw-bold mb-3">Selamat datang di</h4>

                    <h3 class="fw-bold" style="color:#2575fc;">
                        Trust<span class="text-warning">Exam</span>
                    </h3>

                    <img src="{{ asset('assets/img/toga.png') }}" class="img-fluid mt-3" style="max-width:230px;">
                </div>

                <!-- KANAN (FORM LOGIN) -->
                <div class="col-lg-7 p-5" style="background-color:#e4f0ff;">

                    <h4 class="fw-bold text-center mb-4">Silahkan Login</h4>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger p-2">
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username ID</label>
                            <input type="text" name="username" class="form-control rounded-pill"
                                   placeholder="Username ID" value="{{ old('username') }}" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                       class="form-control rounded-pill" placeholder="Password" required>

                                <span class="position-absolute end-0 top-50 translate-middle-y pe-3"
                                      style="cursor:pointer;" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                        </div>

                        <!-- CAPTCHA -->
                        <label class="form-label fw-semibold mt-3">Kode Keamanan</label>
                        <div class="d-flex align-items-center mb-2">

                            <img src="{{ captcha_src('modern') }}" id="captchaImg"
                                 class="border rounded me-2"
                                 style="height:45px;">

                            <button type="button" class="btn btn-outline-primary rounded-circle"
                                    style="width:42px;height:42px;"
                                    onclick="refreshCaptcha()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>

                        <input type="text" name="captcha" class="form-control rounded-pill"
                               placeholder="Masukkan kode di atas" required>


                        <!-- BUTTON LOGIN -->
                        <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold mt-4 py-2">
                            Login
                        </button>
                        <p class="text-center mt-3 mb-0 text-muted small">
                        Lapor Kepada Pengawas Jika Ada Masalah Pada Login
                        </p>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

function refreshCaptcha() {
    const cap = document.getElementById("captchaImg");
    cap.src = "{{ captcha_src('modern') }}?rand=" + Math.random();
}
</script>

</body>
</html>
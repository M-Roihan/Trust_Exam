<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     * Jika user sudah login, langsung alihkan ke dashboard masing-masing.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        if ($request->session()->has('teacher_logged_in')) {
            return redirect()->route('teacher.dashboard');
        }
        if ($request->session()->has('student_logged_in')) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Memproses logika login.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
            'captcha'  => ['required', 'captcha'],
        ], [
            'captcha.captcha' => 'Kode keamanan tidak sesuai.',
        ]);

        $username = $validated['username'];
        $password = $validated['password'];

        // 2. Cek Login GURU
        $teacher = Guru::where('username', $username)->first();
        if ($teacher && Hash::check($password, $teacher->password)) {
            $this->startSession($request, 'teacher', [
                'id'       => $teacher->guru_id, // Sesuai database Anda (guru_id)
                'username' => $teacher->username,
                'name'     => $teacher->nama_guru,
                'role'     => 'Teacher',
                'initials' => $this->generateInitials($teacher->nama_guru),
            ]);

            return redirect()->route('teacher.dashboard');
        }

        // 3. Cek Login SISWA
        $student = Siswa::where('username', $username)->first();
        if ($student && Hash::check($password, $student->password)) {
            // Cek status siswa
            if (strtolower($student->status ?? 'aktif') === 'nonaktif') {
                return back()->withInput()->withErrors(['username' => 'Akun siswa dinonaktifkan. Hubungi admin.']);
            }

            $this->startSession($request, 'student', [
                'id'       => $student->siswa_id, // Sesuai database Anda (siswa_id)
                'username' => $student->username,
                'name'     => $student->nama_siswa,
                'role'     => 'Student',
                'class'    => $student->kelas,
                'initials' => $this->generateInitials($student->nama_siswa),
            ]);

            return redirect()->route('student.dashboard');
        }

        // 4. Cek Login ADMIN
        $admin = Admin::where('username', $username)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $this->startSession($request, 'admin', [
                'id'       => $admin->admin_id, // Sesuai database Anda (admin_id)
                'username' => $admin->username,
                'name'     => $admin->nama_admin,
                'role'     => 'Admin',
                'initials' => $this->generateInitials($admin->nama_admin),
            ]);

            return redirect()->route('admin.dashboard');
        }

        // 5. Gagal Login
        return back()
            ->withInput($request->except('password'))
            ->withErrors(['username' => 'Username atau password salah.']);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'admin_logged_in', 'admin',
            'teacher_logged_in', 'teacher',
            'student_logged_in', 'student',
        ]);
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah keluar dari sesi.');
    }

    /**
     * Helper: Mulai session baru & bersihkan yang lama.
     */
    private function startSession(Request $request, string $type, array $data): void
    {
        // Bersihkan session role lain
        $request->session()->forget(['admin_logged_in', 'admin', 'teacher_logged_in', 'teacher', 'student_logged_in', 'student']);
        
        // Set session baru
        $request->session()->put("{$type}_logged_in", true);
        $request->session()->put($type, $data);
        $request->session()->regenerate();
    }

    private function generateInitials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach ($words as $word) {
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            if (strlen($initials) === 2) break;
        }
        return $initials ?: 'US';
    }
}
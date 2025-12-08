<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    // Daftar role yang diizinkan dalam sistem
    private const ROLES = ['Siswa', 'Guru', 'Admin'];

    // Daftar Kelas untuk dropdown (Konsistensi Data)
    private const CLASS_LIST = [
        'X IPA', 'X IPS', 
        'XI IPA', 'XI IPS', 
        'XII IPA', 'XII IPS'
    ];

    // Daftar Mata Pelajaran untuk dropdown Guru
    private const SUBJECT_LIST = [
        'Bahasa Indonesia', 'Matematika', 'Fisika', 'Biologi', 'Kimia',
        'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi', 'Bahasa Inggris',
        'Penjaskes', 'Seni Budaya', 'TIK'
    ];

    /**
     * Menampilkan daftar user berdasarkan role yang dipilih.
     */
    public function index(Request $request): View
    {
        $role = $this->resolveRole($request->query('role', 'Siswa'));
        $table = $this->buildTableData($role);

        return view('admin.users.index', [
            'activeRole' => $role,
            'tableHeaders' => $table['headers'],
            'tableRows' => $table['rows'],
        ]);
    }

    /**
     * Menampilkan tabel detail data user (versi lengkap).
     */
    public function data(Request $request): View
    {
        $role = $this->resolveRole($request->query('role', 'Siswa'));
        $table = $this->buildDetailTable($role);

        return view('admin.users.data', [
            'activeRole' => $role,
            'tableHeaders' => $table['headers'],
            'tableRows' => $table['rows'],
        ]);
    }

    /**
     * Menampilkan form edit data user.
     */
    public function edit(Request $request, string $role, int $id): View
    {
        $role = $this->resolveRole($role);
        $entity = $this->findEntity($role, $id);
        $redirect = $request->query('redirect', 'index');

        return view('admin.users.edit', [
            'formRole' => $role,
            'entity' => $entity,
            'redirectTarget' => $redirect,
            'statuses' => ['Aktif', 'Nonaktif'],
            'genders' => ['Laki-laki', 'Perempuan'],
            'kelasList' => self::CLASS_LIST,
            'subjectList' => self::SUBJECT_LIST,
        ]);
    }

    /**
     * Memproses update data user ke database.
     */
    public function update(Request $request, string $role, int $id): RedirectResponse
    {
        $role = $this->resolveRole($role);
        $entity = $this->findEntity($role, $id);

        switch ($role) {
            case 'Guru':
                $validated = $request->validate([
                    'nama_guru' => ['required', 'string', 'max:100'],
                    // Validasi unique ke tabel 'teacher' (username tidak boleh kembar)
                    'username' => ['required', 'string', 'max:50', 'unique:teacher,username,'.$entity->getKey().','.$entity->getKeyName()],
                    'password' => ['nullable', 'string', 'min:3'],
                    'matapelajaran' => ['required', 'string', 'max:100'],
                ]);

                $entity->fill([
                    'nama_guru' => $validated['nama_guru'],
                    'username' => $validated['username'],
                    'matapelajaran' => $validated['matapelajaran'],
                ]);
                break;

            case 'Admin':
                $validated = $request->validate([
                    'nama_admin' => ['required', 'string', 'max:100'],
                    'username' => ['required', 'string', 'max:50', 'unique:admin,username,'.$entity->getKey().','.$entity->getKeyName()],
                    'password' => ['nullable', 'string', 'min:3'],
                ]);

                $entity->fill([
                    'nama_admin' => $validated['nama_admin'],
                    'username' => $validated['username'],
                ]);
                break;

            case 'Siswa':
            default:
                $validated = $request->validate([
                    'nama_siswa' => ['required', 'string', 'max:100'],
                    'nis' => ['required', 'string', 'max:20', 'unique:student,nis,'.$entity->getKey().','.$entity->getKeyName()],
                    'username' => ['required', 'string', 'max:50', 'unique:student,username,'.$entity->getKey().','.$entity->getKeyName()],
                    'password' => ['nullable', 'string', 'min:3'],
                    'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                    'kelas' => ['required', 'string', 'max:50'],
                    'tempat_lahir' => ['required', 'string', 'max:100'],
                    'tanggal_lahir' => ['required', 'date'],
                    'status' => ['required', 'in:Aktif,Nonaktif'],
                    'alamat' => ['required', 'string', 'max:255'],
                ]);

                $entity->fill([
                    'nama_siswa' => $validated['nama_siswa'],
                    'nis' => $validated['nis'],
                    'username' => $validated['username'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'kelas' => $validated['kelas'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'status' => $validated['status'],
                    'alamat' => $validated['alamat'],
                    'role' => 'Siswa',
                ]);
                
                // Update password hint jika password diubah
                if (! empty($validated['password'])) {
                    $entity->password_hint = $validated['password'];
                }
                break;
        }

        // Update password jika diisi (berlaku untuk semua role)
        if (! empty($validated['password'])) {
            $entity->password = $validated['password'];
        }

        $entity->save();

        return $this->redirectAfter($request, $role, "Data " . strtolower($role) . " berhasil diperbarui.");
    }

    /**
     * Menghapus data user.
     */
    public function destroy(Request $request, string $role, int $id): RedirectResponse
    {
        $role = $this->resolveRole($role);
        $entity = $this->findEntity($role, $id);

        // Mencegah Admin menghapus dirinya sendiri saat login
        if ($role === 'Admin') {
            $loggedInAdmin = $request->session()->get('admin');
            if ($loggedInAdmin && (int) ($loggedInAdmin['id'] ?? 0) === (int) $entity->getKey()) {
                return $this->redirectAfter($request, $role)
                    ->withErrors(['general' => 'Tidak dapat menghapus akun admin yang sedang digunakan.']);
            }
        }

        $entity->delete();

        return $this->redirectAfter($request, $role, "Data " . strtolower($role) . " berhasil dihapus.");
    }

    /**
     * Menampilkan form tambah user baru.
     */
    public function create(Request $request): View
    {
        $role = $this->resolveRole($request->query('role', 'Siswa'));

        return view('admin.users.create', [
            'formRole' => $role,
            'statuses' => ['Aktif', 'Nonaktif'],
            'genders' => ['Laki-laki', 'Perempuan'],
            'kelasList' => self::CLASS_LIST,
            'subjectList' => self::SUBJECT_LIST,
        ]);
    }

    /**
     * Menyimpan data user baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $admin = $request->session()->get('admin');

        // Pastikan hanya admin terautentikasi yang bisa menyimpan
        if (! $admin || ! isset($admin['id'])) {
            abort(403, 'Admin tidak terautentikasi.');
        }

        $roleType = $this->resolveRole($request->input('role_type', 'Siswa'));

        switch ($roleType) {
            case 'Guru':
                $validated = $request->validate([
                    'nama_guru' => ['required', 'string', 'max:100'],
                    'username' => ['required', 'string', 'max:50', 'unique:teacher,username'],
                    'password' => ['required', 'string', 'min:3'],
                    'matapelajaran' => ['required', 'string', 'max:100'],
                ]);

                Guru::create([
                    'username' => $validated['username'],
                    'password' => $validated['password'],
                    'nama_guru' => $validated['nama_guru'],
                    'matapelajaran' => $validated['matapelajaran'],
                    'admin_id' => $admin['id'],
                ]);
                break;

            case 'Admin':
                $validated = $request->validate([
                    'nama_admin' => ['required', 'string', 'max:100'],
                    'username' => ['required', 'string', 'max:50', 'unique:admin,username'],
                    'password' => ['required', 'string', 'min:3'],
                ]);

                Admin::create([
                    'username' => $validated['username'],
                    'password' => $validated['password'],
                    'nama_admin' => $validated['nama_admin'],
                ]);
                break;

            case 'Siswa':
            default:
                $validated = $request->validate([
                    'nama_siswa' => ['required', 'string', 'max:100'],
                    'nis' => ['required', 'string', 'max:20', 'unique:student,nis'],
                    'username' => ['required', 'string', 'max:50', 'unique:student,username'],
                    'password' => ['required', 'string', 'min:3'],
                    'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                    'kelas' => ['required', 'string', 'max:50'],
                    'tempat_lahir' => ['required', 'string', 'max:100'],
                    'tanggal_lahir' => ['required', 'date'],
                    'status' => ['required', 'in:Aktif,Nonaktif'],
                    'alamat' => ['required', 'string', 'max:255'],
                ]);

                Siswa::create([
                    'nis' => $validated['nis'],
                    'username' => $validated['username'],
                    'password' => $validated['password'],
                    'password_hint' => $validated['password'],
                    'nama_siswa' => $validated['nama_siswa'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'kelas' => $validated['kelas'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'status' => $validated['status'],
                    'alamat' => $validated['alamat'],
                    'role' => 'Siswa',
                    'admin_id' => $admin['id'],
                ]);
                break;
        }

        return redirect()
            ->route('admin.users.index', ['role' => $roleType])
            ->with('status', "Data " . strtolower($roleType) . " berhasil ditambahkan.");
    }

    // --- Helper Functions ---

    // Memastikan role yang diinput valid (default ke 'Siswa')
    private function resolveRole(string $input): string
    {
        return in_array($input, self::ROLES, true) ? $input : 'Siswa';
    }

    // Membangun data untuk tabel index (ringkasan)
    private function buildTableData(string $role): array
    {
        $headers = ['No', 'Nama Pengguna', 'Username', 'Password', 'Role', 'Status', 'Aksi'];

        $rows = match ($role) {
            'Guru' => $this->mapGuruRows(),
            'Admin' => $this->mapAdminRows(),
            default => $this->mapSiswaRows(),
        };

        return compact('headers', 'rows');
    }

    // Mapping data Siswa agar rapi saat ditampilkan
    private function mapSiswaRows()
    {
        return Siswa::query()
            ->orderBy('nama_siswa')
            ->get()
            ->values()
            ->map(function (Siswa $siswa, int $index) {
                $status = $siswa->status ?? 'Aktif';
                return [
                    'id' => $siswa->getKey(),
                    'no' => $index + 1,
                    'name' => $siswa->nama_siswa,
                    'username' => $siswa->username,
                    'password' => '••••••', // Password disamarkan untuk keamanan
                    'role' => 'Siswa',
                    'status' => $status,
                    'status_class' => strtolower($status) === 'aktif' ? 'aktif' : 'nonaktif',
                ];
            });
    }

    // Mapping data Guru
    private function mapGuruRows()
    {
        return Guru::query()
            ->orderBy('nama_guru')
            ->get()
            ->values()
            ->map(function (Guru $guru, int $index) {
                return [
                    'id' => $guru->getKey(),
                    'no' => $index + 1,
                    'name' => $guru->nama_guru,
                    'username' => $guru->username,
                    'password' => '••••••',
                    'role' => 'Guru',
                    'status' => 'Aktif',
                    'status_class' => 'aktif',
                ];
            });
    }

    // Mapping data Admin
    private function mapAdminRows()
    {
        return Admin::query()
            ->orderBy('nama_admin')
            ->get()
            ->values()
            ->map(function (Admin $admin, int $index) {
                return [
                    'id' => $admin->getKey(),
                    'no' => $index + 1,
                    'name' => $admin->nama_admin,
                    'username' => $admin->username,
                    'password' => '••••••',
                    'role' => 'Admin',
                    'status' => 'Aktif',
                    'status_class' => 'aktif',
                ];
            });
    }

    // Membangun data untuk tabel detail (lengkap)
    private function buildDetailTable(string $role): array
    {
        return match ($role) {
            'Guru' => [
                'headers' => ['No', 'Nama Guru', 'Username', 'Mata Pelajaran', 'Aksi'],
                'rows' => Guru::query()->orderBy('nama_guru')->get()->map(fn($guru, $i) => [
                    'id' => $guru->getKey(),
                    'data' => [$i + 1, $guru->nama_guru, $guru->username, $guru->matapelajaran],
                    'actions' => true,
                ]),
            ],
            'Admin' => [
                'headers' => ['No', 'Nama Admin', 'Username', 'Role', 'Aksi'],
                'rows' => Admin::query()->orderBy('nama_admin')->get()->map(fn($admin, $i) => [
                    'id' => $admin->getKey(),
                    'data' => [$i + 1, $admin->nama_admin, $admin->username, 'Admin'],
                    'actions' => true,
                ]),
            ],
            default => [
                'headers' => ['No', 'Nama Lengkap', 'NIS', 'Jenis Kelamin', 'Kelas', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Aksi'],
                'rows' => Siswa::query()->orderBy('nama_siswa')->get()->map(fn($siswa, $i) => [
                    'id' => $siswa->getKey(),
                    'data' => [
                        $i + 1,
                        $siswa->nama_siswa,
                        $siswa->nis ?? '-',
                        $siswa->jenis_kelamin ?? '-',
                        $siswa->kelas ?? '-',
                        $siswa->tempat_lahir ?? '-',
                        $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('M d, Y') : '-',
                        $siswa->alamat ?? '-',
                    ],
                    'actions' => true,
                ]),
            ],
        };
    }

    // Mencari data user di database berdasarkan ID & Role
    private function findEntity(string $role, int $id)
    {
        return match ($role) {
            'Guru' => Guru::findOrFail($id),
            'Admin' => Admin::findOrFail($id),
            default => Siswa::findOrFail($id),
        };
    }

    // Mengatur redirect (kembali ke halaman mana setelah simpan)
    private function redirectAfter(Request $request, string $role, ?string $message = null): RedirectResponse
    {
        $target = $request->input('redirect', 'index');
        $route = $target === 'data' ? 'admin.users.data' : 'admin.users.index';
        $redirect = redirect()->route($route, ['role' => $role]);

        if ($message) {
            $redirect->with('status', $message);
        }

        return $redirect;
    }
}
# 🎓 TrustExam - Sistem Ujian Online Sekolah

![Laravel](https://img.shields.io/badge/Laravel-11%2B-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**TrustExam** adalah platform *Computer Based Test* (CBT) berbasis web yang dirancang untuk mempermudah proses evaluasi belajar di lingkungan sekolah. Dibangun dengan framework Laravel yang handal dan antarmuka Bootstrap 5 yang responsif.

---

## 🚀 Fitur Unggulan

Aplikasi ini dibagi menjadi 3 hak akses (Multi-Auth) dengan fitur spesifik:

### 👨‍💼 Administrator
* **Dashboard Statistik:** Ringkasan jumlah pengguna dan status sistem.
* **Manajemen User:** Kelola data Guru, Siswa, dan Admin (CRUD).
* **Pengaturan Master:** Mengatur kelas, mata pelajaran, dan tahun ajaran.

### 👩‍🏫 Guru
* **Bank Soal:** Membuat dan mengelola paket soal (Pilihan Ganda).
* **Manajemen Ujian:** Menentukan jadwal, durasi, dan kelas target ujian.
* **Rekap Nilai:** Melihat hasil ujian siswa secara *real-time*.

### 👨‍🎓 Siswa
* **Dashboard Informatif:** Melihat jadwal ujian aktif dan pengumuman.
* **Pelaksanaan Ujian:** Antarmuka pengerjaan soal yang fokus dan *user-friendly*.
* **Riwayat:** Melihat histori nilai dari ujian yang telah dikerjakan.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** Laravel (PHP Framework)
* **Frontend:** Blade Template + Bootstrap 5 (CDN)
* **Icons:** Font Awesome 6
* **Database:** MySQL / MariaDB
* **Development Tools:** Composer, NPM (Opsional)

---

## 💻 Panduan Instalasi (Lokal)

Ikuti langkah berikut untuk menjalankan proyek ini di komputer Anda:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/username-anda/Trust_Exam.git](https://github.com/username-anda/Trust_Exam.git)
    cd Trust_Exam
    ```

2.  **Install Dependensi PHP**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Setup Database**
    * Buat database baru di phpMyAdmin bernama `ujian_online`.
    * Buka file `.env` dan sesuaikan koneksi database:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=ujian_online
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Migrasi & Data Awal**
    Jalankan perintah ini untuk membuat tabel (students, teachers, admins, questions, dll) dan mengisi data dummy:
    ```bash
    php artisan migrate --seed
    ```

6.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Buka browser dan akses: `http://localhost:8000`

---

## 🔐 Akun Demo (Default Seeder)

Gunakan akun berikut untuk masuk ke dalam sistem pertama kali:

| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `admin` | `password` |
| **Guru** | `guru01` | `password` |
| **Siswa** | `siswa01` | `password` |


---

## 📂 Struktur Folder Penting

* `app/Models` - Definisi tabel database (*Admin, Guru, Siswa, Question, QuestionSet*).
* `app/Http/Controllers` - Logika aplikasi.
* `resources/views/layouts` - Template utama (*Master Layout*).
* `resources/views/admin` - Halaman khusus Admin.
* `resources/views/guru` - Halaman khusus Guru.
* `resources/views/siswa` - Halaman khusus Siswa.
* `public/assets/css` - File CSS kustom (terpisah dari Bootstrap).

---

### Kontribusi & Tim Pengembang
Proyek ini dikembangkan untuk memenuhi tugas mata kuliah Implementasi Perangkat Lunak.
**Tim Pengembang:** 
    Roihan
    Razzan
    Veliana
    Selvi
    Julius

---


{{-- Menggunakan Layout --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
@php
    $admin = session('admin', [
        'name' => 'Admin System',
        'role' => 'Admin',
    ]);
@endphp

<div class="container-fluid">

    {{-- HEADER MODERN --}}
    <div class="p-4 rounded-4 shadow-sm mb-4 dashboard-header">
        <h2 class="fw-bold text-primary mb-1">Halo, {{ $admin['name'] }} 👋</h2>
        <p class="text-secondary mb-0">Selamat datang di panel administrator TrustExam.</p>
    </div>

    {{-- MENU KOTAK --}}
    <div class="row g-4">

        {{-- Kelola Akun --}}
        <div class="col-md-6">
            <div class="dashboard-card shadow-sm p-4 rounded-4 bg-white d-flex align-items-center">
                
                <div class="icon-box bg-primary-subtle text-primary shadow-sm me-3">
                    <i class="fas fa-users fa-xl"></i>
                </div>

                <div>
                    <h5 class="fw-bold text-primary mb-1">Kelola Akun Pengguna</h5>
                    <p class="text-muted small mb-0">Manajemen data siswa, guru, dan admin.</p>
                </div>

                <a href="{{ route('admin.users.index') }}" class="stretched-link"></a>
            </div>
        </div>

        {{-- Kelola Data --}}
        <div class="col-md-6">
            <div class="dashboard-card shadow-sm p-4 rounded-4 bg-white d-flex align-items-center">
                
                <div class="icon-box bg-warning-subtle text-warning shadow-sm me-3">
                    <i class="fas fa-database fa-xl"></i>
                </div>

                <div>
                    <h5 class="fw-bold text-warning mb-1">Kelola Data Pengguna</h5>
                    <p class="text-muted small mb-0">Rekap data lengkap dan detail.</p>
                </div>

                <a href="{{ route('admin.users.data') }}" class="stretched-link"></a>
            </div>
        </div>

    </div>
</div>

{{-- STYLE --}}
<style>
    /* HEADER */
    .dashboard-header {
        background: #e4f0ff; 
        border-left: 8px solid #2575fc;
    }

    /* CARD MENU */
    .dashboard-card {
        transition: 0.25s ease;
        position: relative;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.9rem 1.6rem rgba(0,0,0,0.1);
    }

    /* ICON BULAT */
    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .bg-primary-subtle {
        background: rgba(37,117,252,0.15) !important;
    }

    .bg-warning-subtle {
        background: rgba(251,192,45,0.25) !important;
    }
</style>

@endsection
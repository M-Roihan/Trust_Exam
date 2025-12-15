@extends('layouts.exam')

@section('title', $exam->nama_ujian)

@section('content')
<div class="container-fluid pb-5">
    
    {{-- Header Info Ujian & Timer --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-0 text-dark">{{ $exam->nama_ujian }}</h4>
            <small class="text-muted">{{ $exam->questionSet->subject }}</small>
        </div>
        <div class="col-md-4 text-md-end">
            {{-- TIMER HITUNG MUNDUR --}}
            <div class="d-inline-block bg-dark text-white px-4 py-2 rounded fw-bold fs-5 shadow">
                <i class="fas fa-stopwatch me-2 text-warning"></i>
                <span id="timer">00:00:00</span>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: SOAL --}}
        <div class="col-lg-9 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="badge bg-primary fs-6">Soal No. {{ $number }}</span>
                </div>
                <div class="card-body p-4">
                    {{-- Pertanyaan --}}
                    <div class="lead mb-4 fw-normal text-dark" style="font-size: 1.1rem; line-height: 1.6;">
                        {!! nl2br(e($currentQuestion->prompt)) !!}
                    </div>

                    {{-- Pilihan Jawaban --}}
                    <form id="answerForm">
                        <div class="d-flex flex-column gap-3">
                            @foreach ($currentQuestion->options as $index => $option)
                                <label class="card card-body border p-3 d-flex flex-row align-items-center cursor-pointer answer-option {{ $existingAnswer && $existingAnswer->jawaban_siswa == $index ? 'border-primary bg-light' : '' }}" 
                                       style="cursor: pointer; transition: all 0.2s;">
                                    
                                    <input type="radio" name="answer" value="{{ $index }}" 
                                           class="form-check-input me-3" 
                                           style="transform: scale(1.3);"
                                           {{ $existingAnswer && $existingAnswer->jawaban_siswa == $index ? 'checked' : '' }}
                                           onchange="saveAnswer({{ $currentQuestion->id }}, {{ $index }})">
                                    
                                    <span class="fw-bold me-3 text-secondary">{{ chr(65 + $index) }}.</span>
                                    <span class="text-dark">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>
                
                {{-- Tombol Navigasi Bawah --}}
                <div class="card-footer bg-white py-3 d-flex justify-content-between">
                    @if($number > 1)
                        <a href="{{ route('student.exam.show', ['exam' => $exam->ujian_id, 'number' => $number - 1]) }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                        </a>
                    @else
                        <button class="btn btn-outline-secondary px-4" disabled>Sebelumnya</button>
                    @endif

                    @if($number < count($questions))
                        <a href="{{ route('student.exam.show', ['exam' => $exam->ujian_id, 'number' => $number + 1]) }}" class="btn btn-primary px-4">
                            Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    @else
                        {{-- Tombol Selesai (Hanya muncul di soal terakhir) --}}
                        <button type="button" onclick="finishExam()" class="btn btn-success px-4 fw-bold">
                            <i class="fas fa-check-circle me-1"></i> Selesai Ujian
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: NAVIGASI NOMOR --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light fw-bold text-dark">Navigasi Soal</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach ($questions as $key => $q)
                            @php
                                $isActive = ($key + 1) == $number;
                            @endphp
                            <a href="{{ route('student.exam.show', ['exam' => $exam->ujian_id, 'number' => $key + 1]) }}" 
                               class="btn btn-sm {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }}" 
                               style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                {{ $key + 1 }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="d-grid">
                <button onclick="finishExam()" class="btn btn-danger fw-bold">
                    <i class="fas fa-stop-circle me-2"></i> Hentikan Ujian
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Form Tersembunyi untuk Submit Selesai --}}
<form id="finishForm" action="{{ route('student.exam.finish', $exam->ujian_id) }}" method="POST" style="display: none;">
    @csrf
</form>

{{-- SCRIPT PENTING: Timer & Auto Save --}}
<script>
    // --- VARIABLES ---
    let timeLeft = {{ $timeLeft }}; 
    const timerDisplay = document.getElementById('timer');
    let isNavigating = false; // Penanda agar tidak dianggap curang saat pindah soal

    // --- 1. SETUP TIMER (FIX DESIMAL) ---
    function updateTimer() {
        if (timeLeft <= 0) {
            timerDisplay.innerText = "00:00:00";
            if(!document.getElementById('finishForm').dataset.submitted) {
                 alert("Waktu Habis! Jawaban Anda akan dikirim otomatis.");
                 forceFinish();
            }
            return;
        }

        let hours = Math.floor(timeLeft / 3600);
        let minutes = Math.floor((timeLeft % 3600) / 60);
        let seconds = Math.floor(timeLeft % 60); 

        // Format 00:00:00
        timerDisplay.innerText = 
            (hours < 10 ? "0" : "") + hours + ":" +
            (minutes < 10 ? "0" : "") + minutes + ":" +
            (seconds < 10 ? "0" : "") + seconds;
        
        // Ubah warna jadi merah jika sisa waktu < 5 menit
        if(timeLeft < 300) {
            timerDisplay.parentElement.classList.remove('bg-dark');
            timerDisplay.parentElement.classList.add('bg-danger');
        }

        timeLeft--;
    }

    setInterval(updateTimer, 1000);
    updateTimer(); 

    // --- 2. FUNGSI NAVIGASI AMAN ---
    // Pasang ini di semua tombol link agar tidak dianggap curang
    document.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('click', () => {
            isNavigating = true; 
        });
    });

    // --- 3. ANTI-CHEATING (LEBIH PINTAR) ---
    const maxViolations = 3;
    
    // Ambil jumlah pelanggaran dari Memory Browser (biar gak reset pas ganti soal)
    let violationCount = localStorage.getItem('violation_count_{{ $exam->ujian_id }}') || 0;

    document.addEventListener("visibilitychange", function() {
        // Jika sedang pindah soal atau ujian sudah selesai, abaikan
        if (isNavigating || document.getElementById('finishForm').dataset.submitted) return;

        if (document.hidden) {
            violationCount++;
            localStorage.setItem('violation_count_{{ $exam->ujian_id }}', violationCount); // Simpan ke memory
            
            let sisaNyawa = maxViolations - violationCount;
            
            if (sisaNyawa > 0) {
                alert(`PERINGATAN KERAS! \nAnda terdeteksi meninggalkan halaman ujian. \n\nSisa kesempatan: ${sisaNyawa} kali lagi sebelum ujian dihentikan otomatis.`);
            } else {
                alert("MAAF, ANDA DIDISKUALIFIKASI.\nAnda terlalu sering meninggalkan halaman ujian.");
                forceFinish();
            }
        }
    });

    // Blokir Klik Kanan
    document.addEventListener('contextmenu', event => {
        event.preventDefault();
        // alert("Klik kanan dimatikan."); // Alert saya matikan biar gak ganggu kalau kepencet
    });

    // Blokir Copy Paste
    document.addEventListener('copy', e => e.preventDefault());
    document.addEventListener('cut', e => e.preventDefault());
    document.addEventListener('paste', e => e.preventDefault());

    // --- 4. DATA SAVING & FINISH ---
    function saveAnswer(questionId, answerIndex) {
        // ... (Kode sama seperti sebelumnya) ...
        // Tambahkan efek visual saja
        document.querySelectorAll('.answer-option').forEach(el => {
            el.classList.remove('border-primary', 'bg-light');
        });
        event.target.closest('label').classList.add('border-primary', 'bg-light');

        fetch("{{ route('student.exam.save_answer') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                exam_result_id: {{ $examResult->hasil_id }},
                question_id: questionId,
                answer_index: answerIndex
            })
        }).catch(err => console.log('Auto-save error:', err));
    }

    function finishExam() {
        isNavigating = true; // Biar gak kena deteksi curang pas klik selesai
        if(confirm("Yakin ingin menyelesaikan ujian?")) {
            forceFinish();
        } else {
            isNavigating = false; // Balikin lagi kalau cancel
        }
    }

    function forceFinish() {
        // Hapus history pelanggaran biar bersih untuk ujian berikutnya (opsional)
        localStorage.removeItem('violation_count_{{ $exam->ujian_id }}');
        
        const form = document.getElementById('finishForm');
        form.dataset.submitted = true;
        form.submit();
    }
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .answer-option:hover { background-color: #f8f9fa; }
    body {
        user-select: none; /* Standar */
        -webkit-user-select: none; /* Chrome/Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE */
    }
</style>
@endsection
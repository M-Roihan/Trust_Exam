@extends('layouts.teacher')

@section('title', $mode === 'edit' ? 'Edit Soal' : 'Input Soal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $mode === 'edit' ? 'Ubah Soal' : 'Input Soal' }}</h4>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $meta['subject'] }}</span>
                <span class="badge bg-secondary">{{ $meta['exam_type'] }}</span>
                <span class="badge bg-info text-dark">{{ $meta['class_level'] }}</span>
            </div>
        </div>
        <a href="{{ route('teacher.questions.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $mode === 'edit' && $questionSet ? route('teacher.questions.update', $questionSet) : route('teacher.questions.store') }}" method="post" id="examForm">
        @csrf
        @if ($mode === 'edit') @method('PUT') @endif
        
        <input type="hidden" name="subject" value="{{ $meta['subject'] }}">
        <input type="hidden" name="exam_type" value="{{ $meta['exam_type'] }}">
        <input type="hidden" name="semester" value="{{ $meta['semester'] }}">
        <input type="hidden" name="class_level" value="{{ $meta['class_level'] }}">

        <div id="question-container">
            @php
            $questionItems = $questions ?? [['prompt' => '', 'options' => ['', '', '', '', ''], 'answer' => 0]];
            @endphp

            @foreach ($questionItems as $index => $question)
                <div class="card mb-4 border-0 shadow-sm question-block" data-index="{{ $loop->index }}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0 fw-bold text-primary question-title">Soal No. {{ $loop->iteration }}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove" onclick="removeQuestion(this)" {{ count($questionItems) <= 1 ? 'disabled' : '' }}>
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pertanyaan</label>
                            <textarea name="questions[{{ $loop->index }}][prompt]" class="form-control" rows="3" placeholder="Tulis soal di sini..." required>{{ $question['prompt'] }}</textarea>
                        </div>
                        
                        <div class="row g-2">
                            <label class="form-label fw-bold small text-muted mb-0">Pilihan Jawaban (Klik radio button untuk kunci jawaban)</label>
                            @foreach ($question['options'] as $optIndex => $option)
                                <div class="col-12">
                                    <div class="input-group">
                                        <div class="input-group-text bg-light">
                                            <input class="form-check-input mt-0" type="radio" 
                                                   name="questions[{{ $loop->parent->index }}][answer]" 
                                                   value="{{ $optIndex }}" 
                                                   {{ $question['answer'] == $optIndex ? 'checked' : '' }}
                                                   required>
                                            <span class="ms-2 fw-bold" style="width: 15px;">{{ chr(65 + $optIndex) }}</span>
                                        </div>
                                        <input type="text" name="questions[{{ $loop->parent->index }}][options][]" 
                                               class="form-control" 
                                               placeholder="Opsi {{ chr(65 + $optIndex) }}" 
                                               value="{{ $option }}" required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card shadow-sm border-0 sticky-bottom mb-4">
            <div class="card-body d-flex justify-content-between bg-light rounded">
                <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">
                    <i class="fas fa-plus"></i> Tambah Soal Lain
                </button>
                <button type="submit" class="btn btn-success px-4 fw-bold">
                    <i class="fas fa-save me-1"></i> Simpan Semua Soal
                </button>
            </div>
        </div>
    </form>
</div>

<template id="question-template">
    <div class="card mb-4 border-0 shadow-sm question-block">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 fw-bold text-primary question-title">Soal Baru</h6>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove" onclick="removeQuestion(this)">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Pertanyaan</label>
                <textarea class="form-control question-prompt" rows="3" placeholder="Tulis soal di sini..." required></textarea>
            </div>
            <div class="row g-2 options-container">
                </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    function addQuestion() {
        const container = document.getElementById("question-container");
        const template = document.getElementById("question-template");
        const clone = template.content.cloneNode(true);
        const newIndex = container.children.length; // Index baru

        // Setup Judul & Tombol Hapus
        clone.querySelector(".question-title").textContent = `Soal No. ${newIndex + 1}`;
        
        // Setup Textarea Name
        const textarea = clone.querySelector("textarea");
        textarea.name = `questions[${newIndex}][prompt]`;

        // Setup Opsi A-E
        const optionsContainer = clone.querySelector(".options-container");
        for (let i = 0; i < 5; i++) {
            const letter = String.fromCharCode(65 + i);
            const div = document.createElement('div');
            div.className = 'col-12';
            div.innerHTML = `
                <div class="input-group">
                    <div class="input-group-text bg-light">
                        <input class="form-check-input mt-0" type="radio" name="questions[${newIndex}][answer]" value="${i}" ${i === 0 ? 'checked' : ''} required>
                        <span class="ms-2 fw-bold" style="width: 15px;">${letter}</span>
                    </div>
                    <input type="text" name="questions[${newIndex}][options][]" class="form-control" placeholder="Opsi ${letter}" required>
                </div>
            `;
            optionsContainer.appendChild(div);
        }

        container.appendChild(clone);
        refreshUI();
    }

    function removeQuestion(btn) {
        if(document.querySelectorAll('.question-block').length <= 1) {
            alert("Minimal harus ada 1 soal!");
            return;
        }
        btn.closest('.question-block').remove();
        refreshUI(); // Re-index nomor urut & name attribute
    }

    function refreshUI() {
        const blocks = document.querySelectorAll('.question-block');
        blocks.forEach((block, index) => {
            // Update Judul
            block.querySelector('.question-title').textContent = `Soal No. ${index + 1}`;
            
            // Update Textarea Name
            block.querySelector('textarea').name = `questions[${index}][prompt]`;

            // Update Radio & Inputs
            const radios = block.querySelectorAll('input[type="radio"]');
            const texts = block.querySelectorAll('input[type="text"]');
            
            radios.forEach(radio => radio.name = `questions[${index}][answer]`);
            texts.forEach(text => text.name = `questions[${index}][options][]`);

            // Update Hapus Button State
            const removeBtn = block.querySelector('.btn-remove');
            removeBtn.disabled = blocks.length === 1;
        });
    }
</script>
@endpush
@endsection
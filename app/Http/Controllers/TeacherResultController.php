<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use Illuminate\View\View;

class TeacherResultController extends Controller
{
    public function index(): View
    {
        $results = ExamResult::with([
            'student',
            'exam.questionSet'
        ])
        ->latest()
        ->paginate(10);

        return view('guru.results.index', compact('results'));
    }
}

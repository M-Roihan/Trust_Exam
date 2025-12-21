<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class TeacherStudentController extends Controller
{
    public function index()
    {
        //  Urutkan nama siswa A-Z (Ascending)
        $student = Siswa::orderBy('nama_siswa', 'asc')->paginate(10);
        
        return view('guru.siswa.index', compact('student'));
    }
}
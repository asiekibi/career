<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorCardController extends Controller
{
    /**
     * Display instructor card requests page
     */
    public function index(): View
    {
        // TODO: Burada eğitmen kimlik kartı taleplerini veritabanından çekilecek
        // Şimdilik boş bir array döndürüyoruz
        $requests = [];
        
        return view('admin.instructor-card-requests', compact('requests'));
    }
}


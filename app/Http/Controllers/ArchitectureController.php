<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ArchitectureController extends Controller
{
    public function index(): View
    {
        return view('architecture');
    }
}

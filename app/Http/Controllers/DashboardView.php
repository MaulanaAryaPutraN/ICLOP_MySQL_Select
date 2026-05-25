<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardView extends Controller
{
    public function index()
    {
        $role = auth()->user()->role; // Ambil role pengguna yang sedang login
        
        return view('dashboard_student', compact('role'));
    }
}

<?php

namespace App\Http\Controllers\NodeJS\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('nodejs.welcome');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    public function welcome()
    {
        return view('report');
        return view('welcome');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}

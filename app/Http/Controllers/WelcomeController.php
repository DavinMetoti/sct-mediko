<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $packages = Package::with('questions.questionDetail')
                    ->where('is_public', true)
                    ->get();
        return view('welcome', compact(['packages']));
    }
}

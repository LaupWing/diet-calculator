<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiController extends Controller
{
    public function generate()
    {
        return view('ai.index');
    }
}

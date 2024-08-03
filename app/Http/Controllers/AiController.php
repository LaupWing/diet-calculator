<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class AiController extends Controller
{
    public function generate()
    {
        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        return view('ai.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiRequest;
use Illuminate\Http\Request;
use OpenAI;

class AiController extends Controller
{
    public function generate(AiRequest $request)
    {
        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));
        $response = $open_ai->chat()->create([
            "model" => "gpt-3.5-turbo-1106",
            "response_format" => [
                "type" => "json_object",
            ],
            "messages" => [
                [
                    "role" => "system",
                    "content" => "
                    "
                ],
                [
                    "role" => "user",
                    "content" => ""
                ]
            ],
            "temperature" => 1.0,
            "max_tokens" => 4000,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
        ]);
        $data = json_decode($response->choices[0]->message->content);
        return view('ai.index');
    }
}

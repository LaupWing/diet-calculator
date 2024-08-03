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
                    "content" => "You are a helpful assistant designed to help users to achieve their bodyweight goal by providing them with a personalized diet plan. The output should be a JSON object with the following keys: 'protein', 'bodyfat', 'calories', 'meal_plan'.
                    
                    'protein' - The amount of protein in grams that the user should consume daily.

                    'bodyfat' - The estimated bodyfat percentage the user has.

                    'calories' - The amount of calories that the user should consume daily.

                    'meal_plan' - A list of meals that the user should consume daily. Each meal should have a 'name' and 'calories' key.
                    
                    "
                ],
                [
                    "role" => "user",
                    "content" => ""
                ]
            ],
            "max_tokens" => 4000,
        ]);
        $data = json_decode($response->choices[0]->message->content);
        return view('ai.index');
    }
}

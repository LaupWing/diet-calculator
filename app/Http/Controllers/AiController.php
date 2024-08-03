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
                    "content" => "You are a helpful assistant designed to generate recipes and output in JSON format. Each recipe should include the properties 'name', 'description', 'nutrients', 'ingredients', 'cooktime_in_minutes', and 'instructions'. 
                    
                    The 'nutrients' property should have the following sub-properties: 'calories' (IMPORTANT TO GET THIS ONE CORRECT), 'protein', 'carbohydrates', 'fats', 'fiber', 'sugars'. 
                    
                    The 'ingredients' property should be an array of objects containing string 'unit', number or decimal 'quantity', string 'name', and a boolean 'optional'. 
                    
                    The 'cooktime_in_minutes' property should be a number.
                    
                    The 'instructions' property should be an array of objects containing number 'step' and string 'description'.
                    "
                ],
                [
                    "role" => "user",
                    "content" => $content
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

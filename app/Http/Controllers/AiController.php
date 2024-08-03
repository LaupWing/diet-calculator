<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiRequest;
use OpenAI;

class AiController extends Controller
{
    public function generate(AiRequest $request)
    {
        $data = $request->validated();

        $age = $data["age"];
        $gender = $data["gender"];
        $height = $data["height"];
        $weight = $data["weight"];
        $activity = $data["activity"];
        $goal_weight = $data["goal_weight"];
        $goal_months = $data["goal_months"];
        $unit = $data["unit"];

        $activities = [
            "sedentary" => "Little or no exercise.",
            "lightly" => "Light exercise 1-3 days a week.",
            "moderately" => "Moderate 3-5 days a week.",
            "very" => "Hard exercise 6-7 days a week.",
            "extra" => "Very hard exercise or physical job.",
        ];

        $activity = $activities[$activity];

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

                    'meal_plan' - A list of meals that the user should consume daily. Each meal should have a 'name' ,'calories', and 'meal_type' key.
                    
                    "
                ],
                [
                    "role" => "user",
                    "content" => "I'm a $gender and $age years old. I'm $height cm tall and weigh $weight $unit. I'm $activity and I want to reach $goal_weight $unit in $goal_months months."
                ]
            ],
            "max_tokens" => 4000,
        ]);

        $data = json_decode($response->choices[0]->message->content);
        logger($data);
        return redirect()->back()->with("data", $data);
    }
}

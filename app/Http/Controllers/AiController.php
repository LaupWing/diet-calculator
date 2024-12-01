<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiRequest;
use App\Models\Guest;
use Inertia\Inertia;
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
        $preferred_cuisine = $data["preferred_cuisine"];
        $dietary_preference = $data["dietary_preference"];

        $guest = Guest::create([
            "age" => $age,
            "gender" => $gender,
            "height" => $height,
            "weight" => $weight,
            "activity" => $activity,
            "goal_weight" => $goal_weight,
            "goal_months" => $goal_months,
            "unit" => $unit,
            "preferred_cuisine" => $preferred_cuisine,
        ]);

        $activities = [
            "sedentary" => "Little or no exercise.",
            "lightly" => "Light exercise 1-3 days a week.",
            "moderately" => "Moderate 3-5 days a week.",
            "very" => "Hard exercise 6-7 days a week.",
            "extra" => "Very hard exercise or physical job.",
        ];

        $preferred_cuisines = [
            "mediterranean" => "Mediterranean",
            "asian" => "Asian",
            "american" => "American",
            "middleEastern" => "Middle Eastern",
            "latinAmerican" => "Latin American",
            "iLoveEverything" => "I love everything",
        ];

        $dietary_preferences = [
            "vegetarian" => "Vegetarian: No meat, may include dairy and eggs.",
            "vegan" => "Vegan: No animal products.",
            "carnivore" => "Carnivore: Primarily meat-based.",
            "pescatarian" => "Pescatarian: No meat except fish.",
            "omnivore" => "Omnivore: No dietary restrictions.",
        ];

        $activity = $activities[$activity];
        $preferred_cuisine = $preferred_cuisines[$preferred_cuisine];
        $dietary_preference = $dietary_preferences[$dietary_preference];

        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        $content = "I'm a $gender and $age years old. I'm $height cm tall and weigh $weight $unit. I'm $activity and I want to reach $goal_weight $unit in $goal_months months.";

        if ($preferred_cuisine === "I love everything") {
            $content .= "For Cuisine. I love everything.";
        } else {
            $content .= " I prefer $preferred_cuisine cuisine.";
        }

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

                    'current_bodyfat' - The exact current bodyfat percentage the user has as a number.

                    'goal_bodyfat' - The exact bodyfat percentage the user aim for as a number.

                    'calories' - The amount of calories that the user should consume daily.

                    'meal_plan' - A list of meals that the user should consume daily. Each meal should have a 'recipe_name'(name of the recipe),'calories', and 'meal_type'(breakfast, lunch, diner, or snack) key.
                    
                    "
                ],
                [
                    "role" => "user",
                    "content" => $content
                ]
            ],
            "max_tokens" => 4000,
        ]);

        $data = json_decode($response->choices[0]->message->content);
        return redirect(route("generated"))->with("data", $data)->with("guest_id", $guest->id);
    }

    public function generated()
    {
        return Inertia::render("Generated");
    }
}

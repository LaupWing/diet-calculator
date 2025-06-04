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
            "dietary_preference" => $dietary_preference,
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

        $content .= " My dietary preference is $dietary_preference.";

        if ($preferred_cuisine === "I love everything") {
            $content .= "For Cuisine. I love everything.";
        } else {
            $content .= " I prefer $preferred_cuisine cuisine.";
        }

        $response = $open_ai->chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a helpful assistant designed to help users achieve their bodyweight goal by providing them with a personalized diet plan. Respond using a JSON object with the following schema exactly. Do not include any explanations, just the JSON."
                ],
                [
                    "role" => "user",
                    "content" => $content
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "diet_plan",
                    "strict" => true,
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "protein" => [
                                "type" => "number",
                                "description" => "Daily protein intake in grams"
                            ],
                            "current_bodyfat" => [
                                "type" => "number",
                                "description" => "Current body fat percentage"
                            ],
                            "goal_bodyfat" => [
                                "type" => "number",
                                "description" => "Target body fat percentage"
                            ],
                            "calories" => [
                                "type" => "number",
                                "description" => "Daily calorie intake"
                            ],
                            "meal_plan" => [
                                "type" => "array",
                                "description" => "Daily meals",
                                "items" => [
                                    "type" => "object",
                                    "properties" => [
                                        "recipe_name" => [
                                            "type" => "string",
                                            "description" => "Name of the recipe"
                                        ],
                                        "calories" => [
                                            "type" => "number",
                                            "description" => "Calories in the meal"
                                        ],
                                        "meal_type" => [
                                            "type" => "string",
                                            "enum" => ["breakfast", "lunch", "dinner", "snack"],
                                            "description" => "Type of the meal"
                                        ]
                                    ],
                                    "required" => ["recipe_name", "calories", "meal_type"],
                                    "additionalProperties" => false
                                ]
                            ]
                        ],
                        "required" => ["protein", "current_bodyfat", "goal_bodyfat", "calories", "meal_plan"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);

        $data = json_decode($response->choices[0]->message->content);
        return redirect(route("generated"))
            ->with("data", $data)
            ->with("guest_id", $guest->id);
    }

    public function generated()
    {
        return Inertia::render("Generated");
    }
}

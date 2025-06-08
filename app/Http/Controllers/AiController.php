<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiRequest;
use App\Models\Guest;
use Inertia\Inertia;
use OpenAI;

class AiController extends Controller
{
    public function generateSummary(AiRequest $request)
    {
        $activities = [
            "sedentary" => "Little or no exercise.",
            "lightly" => "Light exercise 1-3 days a week.",
            "moderately" => "Moderate 3-5 days a week.",
            "very" => "Hard exercise 6-7 days a week.",
            "extra" => "Very hard exercise or physical job.",
        ];

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
        $activityDesc = $activities[$activity];

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

        $content = "I'm a {$data["gender"]} and {$data["age"]} years old. I'm {$data["height"]} cm tall and weigh {$data["weight"]} {$data["unit"]}. I do $activityDesc and I want to reach {$data["goal_weight"]} {$data["unit"]} in {$data["goal_months"]} months.";

        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        $response = $open_ai->chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You're a health assistant. Output only protein (grams), current body fat (percentage), goal body fat (percentage), and target calories. Respond in JSON."
                ],
                [
                    "role" => "user",
                    "content" => $content
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "summary",
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "protein" => ["type" => "number"],
                            "current_bodyfat" => ["type" => "number"],
                            "goal_bodyfat" => ["type" => "number"],
                            "calories" => ["type" => "number"]
                        ],
                        "required" => ["protein", "current_bodyfat", "goal_bodyfat", "calories"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);

        $data = json_decode($response->choices[0]->message->content, true);
        return redirect(route("generated"))
            ->with("data", array_merge($data, ["months" => $goal_months]))
            ->with("guest_id", $guest->id);
    }

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

        $mealSchema = [
            "type" => "object",
            "properties" => [
                "name" => ["type" => "string"],
                "calories" => ["type" => "number"],
                "protein" => ["type" => "number"],
                "carbs" => ["type" => "number"],
                "fats" => ["type" => "number"],
                "instructions" => [
                    "type" => "array",
                    "items" => ["type" => "string"],
                    "description" => "Step-by-step preparation instructions"
                ]
            ],
            "required" => ["name", "calories", "protein", "carbs", "fats", "instructions"],
            "additionalProperties" => false
        ];

        $daySchema = [
            "type" => "object",
            "properties" => [
                "breakfast" => $mealSchema,
                "lunch" => $mealSchema,
                "dinner" => $mealSchema,
                "snack" => $mealSchema
            ],
            "required" => ["breakfast", "lunch", "dinner", "snack"],
            "additionalProperties" => false
        ];

        $response = $open_ai->chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a helpful assistant that generates a 2-day meal plan for health and fitness goals. Each day includes breakfast, lunch, dinner, and snack with macros and instructions. Respond in JSON format matching the schema exactly. No explanations."
                ],
                [
                    "role" => "user",
                    "content" => $content
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "two_day_meal_plan",
                    "strict" => true,
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "protein" => ["type" => "number"],
                            "current_bodyfat" => ["type" => "number"],
                            "goal_bodyfat" => ["type" => "number"],
                            "calories" => ["type" => "number"],
                            "meal_plan" => [
                                "type" => "object",
                                "properties" => [
                                    "day1" => $daySchema,
                                    "day2" => $daySchema
                                ],
                                "required" => ["day1", "day2"],
                                "additionalProperties" => false
                            ]
                        ],
                        "required" => ["protein", "current_bodyfat", "goal_bodyfat", "calories", "meal_plan"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);

        $data = json_decode($response->choices[0]->message->content, true);
        return redirect(route("generated"))
            ->with("data", array_merge($data, ["months" => $goal_months]))
            ->with("guest_id", $guest->id);
    }

    public function generated()
    {
        return Inertia::render("Generated");
    }
}

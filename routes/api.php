<?php

use App\Http\Requests\AiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/get-meals', function (AiRequest $request) {
    $data = $request->validated();

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

    $activity = $activities[$data["activity"]];
    $preferred_cuisine = $preferred_cuisines[$data["preferred_cuisine"]];
    $dietary_preference = $dietary_preferences[$data["dietary_preference"]];

    $content = "I'm a {$data["gender"]} and {$data["age"]} years old. I'm {$data["height"]} cm tall and weigh {$data["weight"]} {$data["unit"]}. I'm $activity and I want to reach {$data["goal_weight"]} {$data["unit"]} in {$data["goal_months"]} months. My dietary preference is $dietary_preference.";

    if ($preferred_cuisine === "I love everything") {
        $content .= " For Cuisine: I love everything.";
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

    $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

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
                    "required" => ["meal_plan"],
                    "additionalProperties" => false
                ]
            ]
        ]
    ]);

    return response()->json(json_decode($response->choices[0]->message->content, true));
})->name('get-meals');

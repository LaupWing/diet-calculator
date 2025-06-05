<?php

namespace App\Jobs;

use App\Mail\SevenDayMealplan;
use App\Models\Meal2;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use OpenAI;

class SevenDayPdf implements ShouldQueue
{
    use Queueable;

    public $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $two_day_meal_plan,
        public $user_info
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipes = $this->generateAllRecipesFromMealPlan($this->generateFull7DayMealPlan(
            $this->two_day_meal_plan,
            $this->user_info['calories'],
            $this->user_info['dietary_preference'],
            $this->user_info['preferred_cuisine']
        ));
        foreach ($recipes as $day => $meals) {
            foreach ($meals as $mealType => $recipe) {
                Meal2::where('day', $day)
                    ->where('meal_type', $mealType)
                    ->where('email', $this->user_info['email'])
                    ->delete();

                Meal2::create([
                    'day' => $day,
                    'meal_type' => $mealType,
                    'name' => $recipe['name'],
                    'calories' => $recipe['calories'],
                    'protein' => $recipe['protein'],
                    'carbs' => $recipe['carbs'] ?? null,
                    'fats' => $recipe['fats'] ?? null,
                    'description' => $recipe['description'] ?? null,
                    'ingredients' => $recipe['ingredients'] ?? [],
                    'instructions' => $recipe['instructions'] ?? [],
                    'serving_suggestions' => $recipe['serving_suggestions'] ?? [],
                    'email' => $this->user_info['email'],
                ]);
            }
        }
        $recipesStructure = [
            "day1" => Meal2::where("day", "day1")->where("email", $this->user_info['email'])->get(),
            "day2" => Meal2::where("day", "day2")->where("email", $this->user_info['email'])->get(),
            "day3" => Meal2::where("day", "day3")->where("email", $this->user_info['email'])->get(),
            "day4" => Meal2::where("day", "day4")->where("email", $this->user_info['email'])->get(),
            "day5" => Meal2::where("day", "day5")->where("email", $this->user_info['email'])->get(),
            "day6" => Meal2::where("day", "day6")->where("email", $this->user_info['email'])->get(),
        ];
        Mail::to($this->user_info['email'])->send(new SevenDayMealplan(
            $this->user_info,
            $recipesStructure
        ));
    }

    public function generateFull7DayMealPlan(
        array $mealPlanDay1And2,
        int $calories,
        string $dietary_preference,
        string $preferred_cuisine
    ) {
        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        // Format day1 and day2 JSON to include in prompt
        $day1Json = json_encode($mealPlanDay1And2['day1'], JSON_PRETTY_PRINT);
        $day2Json = json_encode($mealPlanDay1And2['day2'], JSON_PRETTY_PRINT);

        $content = "I already have a 2-day meal plan as follows:\n\n" .
            "Day 1:\n$day1Json\n\n" .
            "Day 2:\n$day2Json\n\n" .
            "Please generate Day 3 through Day 7 using the same JSON structure and nutritional style. " .
            "Each day should total approximately $calories calories. " .
            "Each day must include: breakfast, lunch, dinner, and snack. " .
            "Each meal must include: name, calories, protein, carbs, fats, and an array of instructions. " .
            "Dietary preference: $dietary_preference. " .
            ($preferred_cuisine === 'I love everything'
                ? "You can vary the cuisine types, I enjoy all kinds of food."
                : "Please focus on $preferred_cuisine cuisine.");

        // Schema: meal
        $mealSchema = [
            "type" => "object",
            "properties" => [
                "name" => ["type" => "string"],
                "calories" => ["type" => "number"],
                "protein" => ["type" => "number"],
                "carbs" => ["type" => "number"],
                "fats" => ["type" => "number"],
            ],
            "required" => ["name", "calories", "protein", "carbs", "fats"],
            "additionalProperties" => false
        ];

        // Schema: day
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

        // Generate new days
        $response = $open_ai->chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a nutrition assistant. Expand a 2-day meal plan into a 7-day plan with consistent structure and macros. Return JSON only. No explanations."
                ],
                [
                    "role" => "user",
                    "content" => $content
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "meal_plan_days_3_to_7",
                    "strict" => true,
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "day3" => $daySchema,
                            "day4" => $daySchema,
                            "day5" => $daySchema,
                            "day6" => $daySchema
                        ],
                        "required" => ["day3", "day4", "day5", "day6"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);

        $newDays = json_decode($response->choices[0]->message->content, true);

        if (is_null($newDays)) {
            throw new Exception("Invalid response from OpenAI API.");
        }

        // Combine all 7 days
        return [
            'meal_plan' => array_merge(
                ['day1' => $mealPlanDay1And2['day1']],
                ['day2' => $mealPlanDay1And2['day2']],
                $newDays
            )
        ];
    }

    public function generateRecipeFromMeal(array $meal): array
    {
        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        $response = $open_ai->chat()->create([
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a recipe assistant that generates detailed recipes based on user preferences."
                ],
                [
                    "role" => "user",
                    "content" =>
                    "Generate a detailed recipe for my fitness plan. " .
                        "Name: {$meal['name']}. " .
                        "Calories: {$meal['calories']}. " .
                        "Protein: {$meal['protein']}. " .
                        "Description: A meal called '{$meal['name']}' with approximately {$meal['calories']} calories, {$meal['protein']}g protein, and a Mediterranean vegetarian style. " .
                        "Additional notes: Use healthy oils and spices. " .
                        "Respond in JSON format matching the provided schema exactly. Do not include any explanations, just the JSON."
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "recipe",
                    "strict" => true,
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "name" => ["type" => "string"],
                            "description" => ["type" => "string"],
                            "calories" => ["type" => "integer"],
                            "protein" => ["type" => "integer"],
                            "carbs" => ["type" => "integer"],
                            "fats" => ["type" => "integer"],
                            "ingredients" => [
                                "type" => "array",
                                "items" => ["type" => "string"]
                            ],
                            "instructions" => [
                                "type" => "array",
                                "items" => [
                                    "type" => "object",
                                    "properties" => [
                                        "title" => ["type" => "string"],
                                        "description" => ["type" => "string"]
                                    ],
                                    "required" => ["title", "description"],
                                    "additionalProperties" => false
                                ]
                            ],
                            "serving_suggestions" => [
                                "type" => "array",
                                "items" => ["type" => "string"]
                            ]
                        ],
                        "required" => [
                            "name",
                            "description",
                            "calories",
                            "protein",
                            "ingredients",
                            "instructions",
                            "serving_suggestions",
                            "carbs",
                            "fats"
                        ],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);

        return json_decode($response->choices[0]->message->content, true);
    }

    public function generateAllRecipesFromMealPlan(array $mealPlan): array
    {
        $detailedRecipes = [];

        foreach ($mealPlan['meal_plan'] as $day => $meals) {
            $detailedRecipes[$day] = [];

            foreach ($meals as $mealType => $meal) {
                try {
                    $recipe = $this->generateRecipeFromMeal($meal);
                    $detailedRecipes[$day][$mealType] = $recipe;
                } catch (Exception $e) {
                    // Optional: fallback or log the error
                    $detailedRecipes[$day][$mealType] = [
                        'error' => "Failed to generate recipe: " . $e->getMessage(),
                        'fallback' => $meal
                    ];
                }
            }
        }

        return $detailedRecipes;
    }
}

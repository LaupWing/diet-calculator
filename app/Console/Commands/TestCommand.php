<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use OpenAI;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateFull7DayMealPlan(
            [
                "day1" => [
                    "breakfast" => [
                        "name" => "Oatmeal with Protein and Fruit",
                        "calories" => 400,
                        "protein" => 30,
                        "carbs" => 60,
                        "fats" => 10,
                        "instructions" => [
                            "Cook 1 cup of oats with water or milk.",
                            "Stir in a scoop of protein powder after cooking.",
                            "Top with 1 sliced banana and a handful of berries."
                        ]
                    ],
                    "lunch" => [
                        "name" => "Grilled Chicken Salad",
                        "calories" => 450,
                        "protein" => 45,
                        "carbs" => 20,
                        "fats" => 18,
                        "instructions" => [
                            "Grill 150g of chicken breast and slice it.",
                            "Toss with mixed greens, cherry tomatoes, cucumber, and avocado.",
                            "Dress with olive oil and vinegar."
                        ]
                    ],
                    "dinner" => [
                        "name" => "Stir-Fried Tofu with Vegetables",
                        "calories" => 500,
                        "protein" => 35,
                        "carbs" => 50,
                        "fats" => 20,
                        "instructions" => [
                            "Cube 200g of firm tofu and pan-fry until golden.",
                            "Add a mix of bell peppers, broccoli, and carrots.",
                            "Stir in soy sauce and serve over 1 cup of cooked brown rice."
                        ]
                    ],
                    "snack" => [
                        "name" => "Greek Yogurt with Honey and Nuts",
                        "calories" => 450,
                        "protein" => 30,
                        "carbs" => 40,
                        "fats" => 20,
                        "instructions" => [
                            "Spoon 1 cup of Greek yogurt into a bowl.",
                            "Drizzle with 1 tablespoon of honey.",
                            "Top with a handful of mixed nuts."
                        ]
                    ]
                ],
                "day2" => [
                    "breakfast" => [
                        "name" => "Scrambled Eggs with Spinach and Whole Grain Toast",
                        "calories" => 350,
                        "protein" => 25,
                        "carbs" => 30,
                        "fats" => 15,
                        "instructions" => [
                            "Scramble 3 eggs in a pan.",
                            "Add a handful of fresh spinach until wilted.",
                            "Serve with 2 slices of whole grain toast."
                        ]
                    ],
                    "lunch" => [
                        "name" => "Turkey and Avocado Wrap",
                        "calories" => 500,
                        "protein" => 40,
                        "carbs" => 45,
                        "fats" => 20,
                        "instructions" => [
                            "Spread hummus on a whole wheat wrap.",
                            "Layer with sliced turkey, avocado, lettuce, and tomato.",
                            "Roll tightly and slice in half."
                        ]
                    ],
                    "dinner" => [
                        "name" => "Baked Salmon with Quinoa and Asparagus",
                        "calories" => 550,
                        "protein" => 40,
                        "carbs" => 40,
                        "fats" => 25,
                        "instructions" => [
                            "Season a salmon fillet with lemon, salt, and pepper, and bake at 200°C for 15 minutes.",
                            "Cook 1 cup of quinoa according to package instructions.",
                            "Steam asparagus until tender and serve with the salmon and quinoa."
                        ]
                    ],
                    "snack" => [
                        "name" => "Cottage Cheese with Pineapple",
                        "calories" => 400,
                        "protein" => 30,
                        "carbs" => 40,
                        "fats" => 10,
                        "instructions" => [
                            "Spoon 1 cup of cottage cheese into a bowl.",
                            "Top with fresh pineapple chunks or canned (in juice)."
                        ]
                    ]
                ]
            ],
            'Lose body fat and maintain muscle',
            'vegetarian',
            'Mediterranean'
        );
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
                "instructions" => [
                    "type" => "array",
                    "items" => ["type" => "string"]
                ]
            ],
            "required" => ["name", "calories", "protein", "carbs", "fats", "instructions"],
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
                            "day6" => $daySchema,
                            "day7" => $daySchema
                        ],
                        "required" => ["day3", "day4", "day5", "day6", "day7"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);
        logger($response->choices[0]->message->content);
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
}

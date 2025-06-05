<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use OpenAI;

class DietInfo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $dietInfo,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '2 Day Diet Plan ~ Loc Nguyen',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.diet-info',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        $meal_plan = $this->dietInfo["meal_plan"];
        $days = ['day1', 'day2'];
        $content = "Based on the following 2-day meal plan, generate expanded instructions for each meal along with a grocery list. The response must be in JSON format matching the provided schema exactly. Do not include any explanations.\n\n";

        foreach ($days as $day) {
            if (!isset($meal_plan[$day])) continue;

            $content .= strtoupper($day) . ":\n";
            foreach ($meal_plan[$day] as $meal_type => $meal) {
                $content .= ucfirst($meal_type) . ":\n";
                $content .= "- Name: {$meal['name']}\n";
                $content .= "- Calories: {$meal['calories']}\n";
                $content .= "- Protein: {$meal['protein']}\n";
                $content .= "- Carbs: {$meal['carbs']}\n";
                $content .= "- Fats: {$meal['fats']}\n\n";
            }
        }

        // Define meal schema
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
                ],
                "ingredients" => [
                    "type" => "array",
                    "items" => ["type" => "string"]
                ],
                "serving_suggestions" => [
                    "type" => "array",
                    "items" => ["type" => "string"]
                ]
            ],
            "required" => ["name", "calories", "protein", "carbs", "fats", "instructions", "ingredients", "serving_suggestions"],
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
                    "content" => "You are a helpful assistant. Expand a 2-day meal plan by providing ingredients, instructions, and serving suggestions for each meal. Also generate a combined grocery list."
                ],
                [
                    "role" => "user",
                    "content" => $content
                ]
            ],
            "response_format" => [
                "type" => "json_schema",
                "json_schema" => [
                    "name" => "meal_plan_expansion",
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
                            ],
                            "grocery_list" => [
                                "type" => "array",
                                "items" => [
                                    "type" => "object",
                                    "properties" => [
                                        "name" => ["type" => "string"],
                                        "quantity" => ["type" => "string"]
                                    ],
                                    "required" => ["name", "quantity"],
                                    "additionalProperties" => false
                                ]
                            ]
                        ],
                        "required" => ["meal_plan", "grocery_list"],
                        "additionalProperties" => false
                    ]
                ]
            ]
        ]);

        $data = json_decode($response->choices[0]->message->content);
        // Generate PDF content in memory
        $pdfContent = Pdf::loadView('pdf.meal_plan', ['data' => $data])->output();

        // Return the PDF as an attachment
        return [
            Attachment::fromData(fn() => $pdfContent, 'Diet_Plan.pdf')
                ->withMime('application/pdf')
        ];
    }
}

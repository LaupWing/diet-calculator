<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
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
            subject: 'Diet Info',
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
        logger($this->dietInfo);
        $open_ai = OpenAI::client(env("OPENAI_API_KEY"));

        $meal_plan = $this->dietInfo["meal_plan"];

        $response = $open_ai->chat()->create([
            "model" => "gpt-3.5-turbo-1106",
            "response_format" => [
                "type" => "json_object",
            ],
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a helpful assistant designed create the meal plan instructions and an grocery list from the meal plan items. The output should be a JSON object with the following keys: 'grocery_list', 'meal_plan_with_instructions'.

                    'grocery_list' - A list of items that the user should buy to prepare the meals. Each item should have a 'name'(name of the item) and 'quantity' key.

                    'meal_plan_with_instructions' - Meal plan is provided by user. Each meal should have a 'recipe_name'(name of the recipe),'calories', 'meal_type'(breakfast, lunch, diner, or snack), and instructions (array with the steps in order) key.

                    "
                ],
                [
                    "role" => "user",
                    "content" => "This is my meal plan $meal_plan"
                ]
            ],
            "max_tokens" => 4000,
        ]);

        $data = json_decode($response->choices[0]->message->content);

        logger(print_r($data, true));
        return [];
    }
}

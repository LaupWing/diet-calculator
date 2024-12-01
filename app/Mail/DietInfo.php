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

                    'current_bodyfat' - The exact current bodyfat percentage the user has as a number.

                    'goal_bodyfat' - The exact bodyfat percentage the user aim for as a number.

                    'calories' - The amount of calories that the user should consume daily.

                    'meal_plan' - A list of meals that the user should consume daily. Each meal should have a 'recipe_name'(name of the recipe),'calories', and 'meal_type'(breakfast, lunch, diner, or snack) key.
                    
                    "
                ],
                [
                    "role" => "user",
                    "content" => ""
                ]
            ],
            "max_tokens" => 4000,
        ]);

        $data = json_decode($response->choices[0]->message->content);
        return [];
    }
}

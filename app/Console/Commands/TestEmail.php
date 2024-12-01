<?php

namespace App\Console\Commands;

use App\Mail\DietInfo;
use App\Models\Guest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

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
        // logger(Guest::first());
        $guest = Guest::where("id", 2)->firstOrFail();
        logger($guest->meals);
        Mail::to("laupwing@gmail.com")->send(new DietInfo([
            "email" => "laupwing@gmail.com",
            "calories" => "1300",
            "current_bodyfat" => "25%",
            "goal_bodyfat" => "15%",
            "protein" => "100g",
            "guest_id" => 2,
            "meal_plan" => $guest->meals,
        ]));
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\DietInfo;
use App\Mail\SevenDayMealplan;
use App\Models\Guest;
use App\Models\Meal2;
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


        $example = [
            "day1" => Meal2::where("day", "day1")->get(),
            "day2" => Meal2::where("day", "day2")->get(),
            "day3" => Meal2::where("day", "day3")->get(),
            "day4" => Meal2::where("day", "day4")->get(),
            "day5" => Meal2::where("day", "day5")->get(),
            "day6" => Meal2::where("day", "day6")->get(),

        ];
        logger($example);
        Mail::to("laupwing@gmail.com")->send(new SevenDayMealplan(
            $example
        ));
    }
}

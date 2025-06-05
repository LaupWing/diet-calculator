<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\ProfileController;
use App\Jobs\SevenDayPdf;
use App\Mail\DietInfo;
use App\Models\Guest;
use App\Models\Submission;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post("/generate", [AiController::class, "generate"])->name("generate");
Route::get("/generated", [AiController::class, "generated"])->name("generated");

Route::post("/submit-email", function (Request $request) {
    $request->validate([
        "email" => ["required", "email"],
    ]);

    $guest  = Guest::where("id", $request->guest_id)->firstOrFail();

    // if ($guest->submissions()->where("email", $request->email)->exists()) {
    //     return redirect()->back()->with("error", "You have already submitted your email.");
    // }
    // $api_key = config("services.beehiiv.secret");

    // Http::withHeaders([
    //     "Authorization" => "Bearer $api_key"
    // ])->post("https://api.beehiiv.com/v2/publications/pub_933eff84-523b-4a44-8fc1-2c0166fa0fd8/subscriptions", [
    //     "email" => $request->email,
    // ]);

    // $guest->submissions()->create([
    //     "email" => $request->email,
    //     "calories" => $request->calories ?? 0,
    //     "current_bodyfat" => $request->current_bodyfat ?? 0,
    //     "goal_bodyfat" => $request->goal_bodyfat ?? 0,
    //     "protein" => $request->protein ?? 0,
    // ]);
    // Mail::to($request->email)->send(new DietInfo([
    //     "email" => $request->email,
    //     "calories" => $request->calories,
    //     "current_bodyfat" => $request->current_bodyfat,
    //     "goal_bodyfat" => $request->goal_bodyfat,
    //     "protein" => $request->protein,
    //     "guest_id" => $request->guest_id,
    //     "meal_plan" => $request->meal_plan,
    // ]));
    Mail::to($request->email)->send(new DietInfo([
        "email" => $request->email,
        "calories" => $request->calories,
        "current_bodyfat" => $request->current_bodyfat,
        "goal_bodyfat" => $request->goal_bodyfat,
        "protein" => $request->protein,
        "guest_id" => $request->guest_id,
        "meal_plan" => $request->meal_plan,
        'age' => $guest->age,
        'gender' => $guest->gender,
        'height' => $guest->height,
        'weight' => $guest->weight,
        'activity' => $guest->activity,
        'preferred_cuisine' => $guest->preferred_cuisine,
        'dietary_preference' => $guest->dietary_preference,
        'goal_weight' => $guest->goal_weight,
        'goal_months' => $guest->goal_months,
        'unit' => $guest->unit,
    ]));

    //  public string $email,
    //     public int $calories,
    //     public string $dietary_preference,
    //     public string $preferred_cuisine,
    //     public $two_day_meal_plan
    SevenDayPdf::dispatch(
        $request->meal_plan,
        [
            'age' => $guest->age,
            'height' => $guest->height,
            'weight' => $guest->weight,
            'activity' => $guest->activity,
            'unit' => $guest->unit,
            'goal_weight' => $guest->goal_weight,
            'goal_months' => $guest->goal_months,
            'preferred_cuisine' => $guest->preferred_cuisine,
            'dietary_preference' => $guest->dietary_preference,
            'current_bodyfat' => $request->current_bodyfat,
            'goal_bodyfat' => $request->goal_bodyfat,
            'calories' => $request->calories,
            'protein' => $request->protein,
            'email' => $request->email,
        ]
    );

    // foreach ($request->meal_plan as $meal) {
    //     $guest->meals()->create([
    //         "recipe_name" => $meal["recipe_name"],
    //         "calories" => $meal["calories"],
    //         "meal_type" => $meal["meal_type"],
    //     ]);
    // }

    return redirect()->back()->with("data", [
        "email" => $request->email,
        "calories" => $request->calories,
        "current_bodyfat" => $request->current_bodyfat,
        "goal_bodyfat" => $request->goal_bodyfat,
        "protein" => $request->protein,
        "guest_id" => $request->guest_id,
        "meal_plan" => $request->meal_plan,
    ]);
})->name("submit-email");

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

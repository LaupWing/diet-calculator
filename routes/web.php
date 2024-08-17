<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\ProfileController;
use App\Mail\DietInfo;
use App\Models\Guest;
use App\Models\Submission;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
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

    $guest->submissions()->create([
        "email" => $request->email,
        "calories" => $request->calories ?? 0,
        "current_bodyfat" => $request->current_bodyfat ?? 0,
        "goal_bodyfat" => $request->goal_bodyfat ?? 0,
        "protein" => $request->protein ?? 0,
    ]);
    Mail::to($request->email)->send(new DietInfo([
        "calories" => $request->calories,
        "current_bodyfat" => $request->current_bodyfat,
        "goal_bodyfat" => $request->goal_bodyfat,
        "protein" => $request->protein,
        "guest_id" => $request->guest_id,
        "meal_plan" => $request->meal_plan,
    ]));

    foreach ($request->meal_plan as $meal) {
        $guest->meals()->create([
            "recipe_name" => $meal["recipe_name"],
            "calories" => $meal["calories"],
            "meal_type" => $meal["meal_type"],
        ]);
    }

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

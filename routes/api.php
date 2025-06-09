<?php

use App\Http\Controllers\Generate2MealsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/get-meals', function (Request $request) {
    $data = $request->all();

    return response()->json(
        (new Generate2MealsController())->index($data["guest_id"])
    );
})->name('get-meals');

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal2 extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'meal_type',
        'name',
        'calories',
        'protein',
        'carbs',
        'fats',
        'description',
        'ingredients',
        'instructions',
        'serving_suggestions',
        'email',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'serving_suggestions' => 'array',
    ];
}

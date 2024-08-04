<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        "recipe_name",
        "calories",
        "meal_type"
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}

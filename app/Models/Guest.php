<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;
    protected $fillable = [
        "age",
        "gender",
        "height",
        "weight",
        "activity",
        "goal_weight",
        "goal_months",
        "unit",
        "preferred_cuisine"
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}

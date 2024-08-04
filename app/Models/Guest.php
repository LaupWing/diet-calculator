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
    ];
}

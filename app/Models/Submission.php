<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        "email",
        "calories",
        "current_bodyfat",
        "goal_bodyfat",
        "guest_id"
    ];
}

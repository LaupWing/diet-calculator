<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "gender" => ["required", "string"],
            "age" => ["required", "integer"],
            "height" => ["required", "integer"],
            "weight" => ["required", "integer"],
            "activity" => ["required", "string"],
            "goal_weight" => ["required", "integer"],
            "goal_months" => ["required", "integer"],
            "unit" => ["required", "string"],
        ];
    }
}

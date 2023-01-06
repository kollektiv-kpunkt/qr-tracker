<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "required|string|max:255",
            "description" => "string|nullable|max:255",
            "link" => "required|string|max:255",
            "fg_color" => "required|string|max:7",
            "bg_color" => "required|string|max:7",
            "user_id" => "required|integer"
        ];
    }
}

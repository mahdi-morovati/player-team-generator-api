<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'position' => 'required|string',
            'playerSkills' => 'required|array',
            'playerSkills.*' => 'required',
            'playerSkills.*.name' => 'required|string',
            'playerSkills.*.value' => 'required|integer',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectTeamRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'requirement' => 'required',
            'requirement.*.position' => 'required|distinct|string',
            'requirement.*.mainSkill' => 'required|string',
            'requirement.*.numberOfPlayers' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        $messages = [];
        $requirement = $this->request->all()['requirement'];
        foreach ($this->request as $key => $value) {
            foreach ($value as $index => $item) {
                $messages["requirement.{$index}.position.required"] = __('request.SelectTeamRequest.required', ['parameter' => 'position', 'index' => $index]);
                $messages["requirement.{$index}.mainSkill.required"] = __('request.SelectTeamRequest.required', ['parameter' => 'mainSkill', 'index' => $index]);
                $messages["requirement.{$index}.numberOfPlayers.required"] = __('request.SelectTeamRequest.required', ['parameter' => 'numberOfPlayers', 'index' => $index]);

                if (isset($requirement[$index]['position'])) $messages["requirement.{$index}.position.string"] = __('request.SelectTeamRequest.string', ['parameter' => 'position', 'value' => $requirement[$index]['position'], 'index' => $index]);
                if (isset($requirement[$index]['mainSkill'])) $messages["requirement.{$index}.mainSkill.string"] = __('request.SelectTeamRequest.string', ['parameter' => 'mainSkill', 'value' => $requirement[$index]['mainSkill'], 'index' => $index]);
                if (isset($requirement[$index]['numberOfPlayers'])) $messages["requirement.{$index}.numberOfPlayers.integer"] = __('request.SelectTeamRequest.integer', ['parameter' => 'numberOfPlayers', 'value' => $requirement[$index]['numberOfPlayers'], 'index' => $index]);

                if (isset($item['position'])) $messages["requirement.{$index}.position.distinct"] = __('request.SelectTeamRequest.distinct', ['attribute' => $item['position']]);
            }
        }
        return $messages;

    }


}

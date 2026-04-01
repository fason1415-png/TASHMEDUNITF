<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'channel' => ['nullable', Rule::in(config('shiforeyting.channel_values'))],
            'language' => ['nullable', Rule::in(array_keys(config('shiforeyting.supported_locales', [])))],
            'verified_token' => ['nullable', 'string', 'max:128'],
            'callback_requested' => ['nullable', 'boolean'],
            'callback_contact' => ['nullable', 'string', 'max:255'],
            'callback_note' => ['nullable', 'string', 'max:1000'],
            'doctor_id' => ['nullable', 'integer', 'exists:doctors,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'service_point_id' => ['nullable', 'integer', 'exists:service_points,id'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => ['nullable'],
            'device_fingerprint' => ['nullable', 'string', 'max:512'],
        ];
    }
}


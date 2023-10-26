<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventPost extends FormRequest
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
            'event_type_id' => ['required'],
            'event_name' => ['required'],
            'hosted_by' => ['required'],
            'rsvp_start_time' => ['required'],
            'rsvp_start_timezone' => ['required'],
        ];
    }
}

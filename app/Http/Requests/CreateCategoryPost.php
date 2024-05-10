<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryPost extends FormRequest
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
            'category_name.*' => ['required', 'unique:event_design_categories,category_name']
        ];
    }

    public function messages()
    {
        return [
            'category_name.*.required' => 'Please select category name.',
            'category_name.*.unique' => 'category is duplicate.',

        ];
    }
}

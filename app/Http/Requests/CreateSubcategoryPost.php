<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubcategoryPost extends FormRequest
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

    public function rules()
    {
        return [
            'event_design_category_id' => ['required'],
            'subcategory_name.*' => ['required', 'unique:event_design_sub_categories,subcategory_name']
        ];
    }

    public function messages()
    {
        return [
            'event_design_category_id' => 'Please select category',
            'subcategory_name.*.required' => 'Please enter subcategory name.',
            'subcategory_name.*.unique' => 'subcategory is duplicate.',

        ];
    }
}

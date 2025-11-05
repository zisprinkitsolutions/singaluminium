<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobProjectStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required',
            'project_description' => 'required',
            'customer_id' => 'required|integer',
        ];

    }

    public function messages(){
        return [
            'project_id.required' => 'Project name is required',
            'project_description.required' => 'Project description is required',
            'customer_id.required' => 'Customer name is required',
            'customer_id.integer' => 'Customer name must be an integer',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
        ];
    }
}


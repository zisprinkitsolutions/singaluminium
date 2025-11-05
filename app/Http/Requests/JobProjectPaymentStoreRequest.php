<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobProjectPaymentStoreRequest extends FormRequest
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
            'job_project_id' => 'required|integer',
            'party_info_id' => 'required|integer',
            'payment_amount' => 'required',
            'date' => 'required',
        ];
    }

    public function messages(){
        return [
            'job_project_id.required' => 'Project name is required',
            'party_info_id.required' => 'Customer name is required',
        ];
    }
}

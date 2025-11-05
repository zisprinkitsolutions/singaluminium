<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartyInfoStoreRequest extends FormRequest
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
            'pi_name' => 'required',
            'pi_type' => 'required',
            'trn_no' => 'required',
            'address' => 'required',
            'con_person' => 'required',
            'con_no' => 'required',
            'phone_no' => 'required',
        ];
    }

    public function messages(){
        return [
            'pi_name.required' => 'Party name is required',
            'pi_type.required' => 'Party type is required',
            'con_person.required' => 'Contract person is required',
            'con_no.required' => 'Phone number is required',
            'phone_no.required' => 'Mobile phone number is required',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanySignUpStepThree extends CustomFormRequest
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
			'services' => 'required',
            'date_established' => 'required|numeric',
            "complaints" => "required",
            'liability_amount' => 'requiredIf:liability,true',
            'liability_expires' => 'requiredIf:liability,true'

        ];
    }

    public function messages()
    {
	    return [
		    'liability_amount.requiredIf' => 'Liability amount is required!',
		    'liability_expires.requiredIf' => 'Liability expires date is required!',
	    ];
    }
}

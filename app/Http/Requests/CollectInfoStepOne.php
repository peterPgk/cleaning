<?php

namespace App\Http\Requests;

class CollectInfoStepOne extends CustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Todo: Check for signed up user
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
            'name' => 'required',
            'company_number' => 'required',
            'trading_name' => 'required',
            'website' => 'url',
            'phone' => 'numeric',
            'phone_2' => 'numeric',
            'email' => 'email',
            'vat' => 'numeric',
            'address' => 'required',
            'address_3' => '',
            'logo' => 'image,required'
        ];
    }
}

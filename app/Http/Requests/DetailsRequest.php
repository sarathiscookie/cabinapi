<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailsRequest extends FormRequest
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
        if($this->request->get('updateContact') == 'updateContact') {
            $rules = [
                'firstname'     => 'required|max:100',
                'lastname'      => 'required|max:100',
                'telephone'     => 'required|max:25',
                'mobile'        => 'max:25',
                'zip'           => 'required|max:25',
                'city'          => 'required|max:255',
                'street'        => 'required|max:255',
                'country'       => 'required|max:255',
            ];
        }

        if($this->request->get('updateBilling') == 'updateBilling') {
            $rules = [
                'company'       => 'required|max:255',
                'zip'           => 'required|max:25',
                'city'          => 'required|max:255',
                'street'        => 'required|max:255',
                'legal'         => 'required|not_in:0',
                'tax'           => 'required|max:100',
                'vat'           => 'required|max:100',
                'fax'           => 'required|max:50',
            ];
        }

        if($this->request->get('updateCabin') == 'updateCabin') {
            $rules = [
                'cabinname'          => 'required|max:200',
                'height'             => 'required|max:15',
                'club'               => 'required|max:200',
                'cancel'             => 'required|not_in:0',
                'payment'            => 'required',
                'deposit'            => 'required|max:15',
                'website'            => 'required|max:100',
                'region'             => 'required|not_in:0',
                'latitude'           => 'required|max:100',
                'longitude'          => 'required|max:100',
            ];
        }

        return $rules;
    }
}

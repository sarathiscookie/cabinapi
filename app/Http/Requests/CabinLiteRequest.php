<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CabinLiteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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
        $rules = [];
        if($this->request->get('createCabin') == 'createCabin') {
            $rules = [

                'cabin_name' => [
                    'required',
                    Rule::unique('cabins', 'name')->where(function($query) {
                        $query->where('is_delete', 0);
                    }),
                ],
                'cabin_code' => [
                    'required ',
                    'max:10',
                    Rule::unique('cabins', 'invoice_code')->where(function($query) {
                        $query->where('is_delete', 0);
                    }),
                ],

                'height'          => 'required',
               // 'availability'    => 'required|max:200',
                //'club'            => 'required|max:200',
                'check_in'        => 'required|max:100',
                'check_out'       => 'required|max:100',
                'deposit'         => 'required|numeric',
                'cabin_owner'     => 'required',
                'country'         => 'required',
                //  'zip'             => 'required|max:25',
                // 'city'            => 'required|max:255',
                'halfboard_price' => 'required_if:halfboard,1',
                'booking_type'  => 'required',
            ];
        }

        if($this->request->get('formPart') == 'updateCabin') {
            $rules = ['height' => 'required',
                'check_in' => 'required|max:100',
                'check_out' => 'required|max:100',
                'deposit' => 'required|numeric',
                'cabin_owner' => 'required',
                'country' => 'required',
                'halfboard_price' => 'required_if:halfboard,1',
                'booking_type' => 'required'];
        }

         if($this->request->get('formPart') == 'updateContactInfo') {
             $rules = array(
                 'firstname' => 'required',
                 'lastname' => 'required',
                 'mobile' => 'required',
                 'usrCountry' => 'required',
                 'telephone' => 'required',
                 'usrZip' => 'required',
                 'usrAddress' => 'required',
                 'usrZip' => 'required',
                 'usrCity' => 'required',
             );
         }
        if($this->request->get('formPart') == 'updateBillingInfo') {
            $rules = array(
                'company' => 'required',
             //   'city' => 'required',
                //  'zip' => 'required',
            );
        }


        return $rules;



    }

    public function messages()
    {
        return [
            'halfboard_price.required_if'  => 'The Price field is required when Half Board field is checked.',
            'usrZip.required'              => 'The Zip field is required.',
            'usrCity.required'             => 'The city field is required.',
            'usrAddress.required'          => 'The street field is required.',
            'usrCountry.required'          => 'The country field is required.',
        ];
    }
}

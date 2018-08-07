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
                'check_in'        => 'required|max:100',
                'check_out'       => 'required|max:100',
                'deposit'         => 'required|numeric',
                'cabin_owner'     => 'required',
                'country'         => 'required',
                'halfboard_price' => 'required_if:halfboard,1',
                'booking_type'    => 'required',
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
                // 'mobile' => 'required',
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

    /**
     * Get the validation attribute that apply to the request.
     *
     * @return array
     */
    public function   attributes()
    {
        if (\Lang::locale() == 'de') {
            return ['firstname' => 'Der Vornmae',
                'lastname' => 'Der Nachname',
                'mobile' => 'Die Handynummer',
                'telephone' => 'Die Telefonnummer ',
                'Zip' => 'Die Zip',
                'city' => 'Die Stadt',
                'usrCity' => 'Die Stadt',
                'street' => 'Die Straße',
                'usrAddress' => 'Die Straße',
                'usrZip' => 'Die Zip',
                'country' => 'Das Land',
                'usrCountry' => 'Das Land',
                'cabin_name' => 'Die Hütte',
                'cabin_code' => 'Die Hüttenkürzel',
                'height' => 'Die Höhe ',
                'deposit' => 'Die Anzahlung',
                'cabin_owner' => 'Der  Hüttenbesitzer',
                'tours' => 'Touren',
                'booking_type' => 'Hütte ungsarten',
                'availability' => 'Erreichbar von',
                'check_in' => 'Der Check-in ab',
                'check_out' => 'Der Check-in bis',
                'company' => 'Der Firmenname',
                'usrCompany' => 'Der Firmenname',];
        }else {
            return [];
        }
    }

    /**
     * Get the validation message
     *
     * @return array
     */
    public function messages()
    {

        if (\Lang::locale() == 'de') {
            return [
                'halfboard_price.required_if' => 'Das Preis ist erforderlich, wenn das Feld Halbpension markiert ist.',
                'usrZip.required' => ' Die Postleizahl muss angegeben werden.',
                'usrCity.required' => 'Die Stadt muss angegeben werden.',
                'usrAddress.required' => 'Das Stadt muss angegeben werden.',
                'usrCountry.required' => 'Das Land muss angegeben werden.',
                 'deposit.numeric' => 'Bitte Zahl angeben'


            ];
        }
        else{
            return [    'halfboard_price.required_if' => 'The Price field is required when Half Board field is checked.',
                'usrZip.required' => 'The Zip field is required.',
                'usrCity.required' => 'The city field is required.',
                'usrAddress.required' => 'ddd The street field is required.',
                'usrCountry.required' => 'The country field is required.'
                ];

        }

    }
}

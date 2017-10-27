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
                'firstname'     => 'required',
                'lastname'      => 'required',
                'telephone'     => 'required',
                'zip'           => 'required',
                'city'          => 'required',
                'street'        => 'required',
                'country'       => 'required',
            ];
        }

        return $rules;
    }
}

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

        return $rules;
    }
}

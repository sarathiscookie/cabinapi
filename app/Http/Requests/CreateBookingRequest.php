<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
            'firstname' => 'required|max:100',
            'lastname'  => 'required|max:100',
            'street'    => 'max:255',
            'city'      => 'max:255',
            'country'   => 'required',
            'zip'       => 'max:25',
            'email'     => 'required|max:255|unique:user,usrEmail',
            'mobile'    => 'max:20',
            'phone'     => 'max:20',
            'comments'  => 'max:300',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabinownerBookingRequest extends FormRequest
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

        if(session('sleeping_place') != 1)
        {
            $rules = [
                'bookingDate' => 'required',
                'beds' => 'required_without:dorms',
                'dorms' => 'required_without:beds',
            ];
        }
        else {
            $rules = [
                'bookingDate' => 'required',
                'sleeps' => 'required|not_in:0',
            ];
        }

        return $rules;
    }
}

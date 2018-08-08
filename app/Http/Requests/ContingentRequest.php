<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContingentRequest extends FormRequest
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
            'reservation_type'        => 'required|not_in:2',
            'normal_beds'             => 'required|numeric',
            'normal_dorms'            => 'required|numeric',
            'normal_emergency_rooms'  => 'numeric|nullable',
            'normal_inquiry_guest'    => 'numeric|nullable',
            'normal_ms_inquiry_guest' => 'numeric|nullable',
        ];
    }
}

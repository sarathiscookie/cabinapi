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
        $rules =  [
            'reservation_type'        => 'required|not_in:2',
            'normal_beds'             => 'required|numeric',
            'normal_dorms'            => 'required|numeric',
            'normal_emergency_rooms'  => 'numeric|nullable',
            'normal_inquiry_guest'    => 'numeric|nullable',
            'normal_ms_inquiry_guest' => 'numeric|nullable',
        ];

        if($this->request->get('notRegularCheckbox') === '1') {
            $rules =  [
                'not_regular_date'             => 'required',
                'not_regular_beds'             => 'required|numeric',
                'not_regular_dorms'            => 'required|numeric',
                'not_regular_emergency_rooms'  => 'numeric|nullable',
                'not_regular_inquiry_guest'    => 'numeric|nullable',
                'not_regular_ms_inquiry_guest' => 'numeric|nullable',
            ];
        }

        if($this->request->get('regularCheckbox') === '1') {

            if($this->request->get('monday') === '1') {
                $rules =  [
                    'mon_beds'                     => 'required|numeric',
                    'mon_dorms'                    => 'required|numeric',
                    'mon_emergency_rooms'          => 'numeric|nullable',
                    'mon_inquiry_guest'            => 'numeric|nullable',
                    'mon_ms_inquiry_guest'         => 'numeric|nullable',

                ];
            }
            if($this->request->get('tuesday') === '1') {
                $rules =  [
                    'tue_beds'                     => 'required|numeric',
                    'tue_dorms'                    => 'required|numeric',
                    'tue_emergency_rooms'          => 'numeric|nullable',
                    'tue_inquiry_guest'            => 'numeric|nullable',
                    'tue_ms_inquiry_guest'         => 'numeric|nullable',
                ];
            }
            if($this->request->get('wednesday') === '1') {
                $rules =  [
                    'wed_beds'                     => 'required|numeric',
                    'wed_dorms'                    => 'required|numeric',
                    'wed_emergency_rooms'          => 'numeric|nullable',
                    'wed_inquiry_guest'            => 'numeric|nullable',
                    'wed_ms_inquiry_guest'         => 'numeric|nullable',
                ];
            }
            if($this->request->get('thursday') === '1') {
                $rules =  [
                    'thu_beds'                     => 'required|numeric',
                    'thu_dorms'                    => 'required|numeric',
                    'thu_emergency_rooms'          => 'numeric|nullable',
                    'thu_inquiry_guest'            => 'numeric|nullable',
                    'thu_ms_inquiry_guest'         => 'numeric|nullable',
                ];
            }
            if($this->request->get('friday') === '1') {
                $rules =  [
                    'fri_beds'                     => 'required|numeric',
                    'fri_dorms'                    => 'required|numeric',
                    'fri_emergency_rooms'          => 'numeric|nullable',
                    'fri_inquiry_guest'            => 'numeric|nullable',
                    'fri_ms_inquiry_guest'         => 'numeric|nullable',
                ];
            }
            if($this->request->get('saturday') === '1') {
                $rules =  [
                    'sat_beds'                     => 'required|numeric',
                    'sat_dorms'                    => 'required|numeric',
                    'sat_emergency_rooms'          => 'numeric|nullable',
                    'sat_inquiry_guest'            => 'numeric|nullable',
                    'sat_ms_inquiry_guest'         => 'numeric|nullable',
                ];
            }
            if($this->request->get('sunday') === '1') {
                $rules =  [
                    'sun_beds'                     => 'required|numeric',
                    'sun_dorms'                    => 'required|numeric',
                    'sun_emergency_rooms'          => 'numeric|nullable',
                    'sun_inquiry_guest'            => 'numeric|nullable',
                    'sun_ms_inquiry_guest'         => 'numeric|nullable',
                ];
            }
        }

        return $rules;
    }
}